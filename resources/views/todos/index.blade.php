<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Todo Task</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
  	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" />
  	<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
	<div class="container mt-5 pt-5">
		<div class="row">
			<div class="col-md-12">
				<h3 class="text-info">PHP - Simple To Do List App</h3>
				<hr/>
			</div>
		</div>
		<div class="row">
			<div class="col-md-10 offset-2">
				<form method="post" onsubmit="return false" id="add_task_form">
					<div class="row">
						<div class="col-md-5 mb-3">
							<input type="text" name="task" class="form-control input-task" />
							<div id="error" class="text-danger"></div>
						</div>
						<div class="col-md-5">
							<button class="btn btn-primary" id="btnAddTask">Add Task</button>
							<button onclick="showAllTasks()" class="btn btn-info">Show All Tasks</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<table class="table table-stripped">
					<thead>
						<tr>
							<th>#</th>
							<th>Task</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody id="html_todoList">
						<!-- <tr class="row-1">
							<td>1</td>
							<td>Check Erros</td>
							<td>Done</td>
							<td>
								<button class="btn btn-danger btm-sm"><i class="fa fa-trash"></i></button>
							</td>
						</tr>
						<tr class="row-2">
							<td>2</td>
							<td>Check Erros</td>
							<td>Done</td>
							<td>
								<button class="btn btn-success btm-sm"><i class="fa fa-edit"></i></button> | 
								<button class="btn btn-danger btm-sm"><i class="fa fa-trash"></i></button>
							</td>
						</tr> -->
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="modal fade" id="deleteTaskModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	    <div class="modal-dialog modal-dialog-centered modal-sm">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel">Delete Task</h5>
	                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	            </div>
	            <div class="modal-body">
	               	<div class="row">
	               		<div class="col-md-12">
	               			<p>Are you sure to this task?</p>
	               			<input type="hidden" name="todo_id" class="todo_id" value="">
	               		</div>
	               	</div>
	            </div>
	            <div class="modal-footer">
	            	<button type="button" class="btn btn-danger" id="btnDeleteTodo">Delete</button>
	            </div>
	        </div>
	    </div>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			$('#btnAddTask').on('click',function(){
				var task 	=	$('.input-task').val();
				$.ajax({
	                url: '/addTodo',
	                type: 'POST',
	                data: {
	                    _token: $('meta[name="csrf-token"]').attr('content'),
	                    task: task
	                },
	                jsonType:'json',
	                success: function(response) {
	                	console.log(response.errors);
	                    if(response.status=='true'){
	                    	toastr.success(response.message);
	                    	fetchToDoList();
	                    	$('.input-task').val('');
	                    	$('.input-task').removeClass(' is-valid');
	                    }else if(response.status=='false'){
	                    	toastr.warning(response.message);
	                    }else{
	                    	console.log(response.errors);
	                    	$.each(response.errors, function(key, value) {
		                        if (value.length > 0) {
		                            $('.input-' + key).addClass('is-invalid');
		                            $('.input-' + key).parents('.mb-3').find('#error').html(value[0]);
		                        }
		                    });
	                    }
	                },
	                error: function(xhr, status, error) {
		                console.error(xhr.responseText);
		                toastr.error('An error occurred while processing your request.');
		            }
	            });
			})
		});
		$('#add_task_form input').on('keyup', function () { 
	        $(this).removeClass('is-invalid').addClass('is-valid');
	        $(this).parents('.mb-3').find('#error').html(" ");
	    });
	    fetchToDoList();
	    function showAllTasks(){
	    	fetchToDoList();
	    }
		function fetchToDoList(){
			$.ajax({
                url: '/getTodoList',
                type: 'GET',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                jsonType:'json',
                success: function(response) {
                	console.log(response);
                	$('#html_todoList').html(response.todoList);
                }
            });
		}
		function changeStatus(id){
			$.ajax({
                url: `/todos/${id}`,
                type: 'PUT',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id
                },
                jsonType:'json',
                success: function(response) {
                	if(response.status=='true'){
                		toastr.success(response.message);
                		$('.row-'+id).remove();
                	}else{
                		toastr.warning(response.message);
                	}
                }
            });
		}
		function deleteStatus(id){
			$('#deleteTaskModal').modal('show');
			$('.todo_id').val(id);
		}
		$(document).on('click','#btnDeleteTodo',function(){
			var id = $('.todo_id').val();
			$.ajax({
                url: `/todos/${id}`,
                type: 'delete',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id
                },
                jsonType:'json',
                success: function(response) {
                	if(response.status=='true'){
                		toastr.success(response.message);
                		$('#deleteTaskModal').modal('hide');
                		fetchToDoList();
                	}else{
                		toastr.warning(response.message);
                	}
                }
            });
		})
	</script>
</body>
</html>