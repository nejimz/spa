<template>
	<div>
		<h2>Comments</h2>
        <div class="row">
            <div class="col-sm-4">
                <form id="comment-form" @submit.prevent="submitForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" value="" v-model="name">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Comment</label>
                        <textarea class="form-control" id="message" rows="3" v-model="message"></textarea>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>

		<div class="card card-body" v-for="comment in comments" v-bind:key="comment.id">
			<h5>{{ comment.name }}</h5>
			<p>{{ comment.message }}</p>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				name : '',
				message : '',

				comments : [],
				comment : {
					id : '',
					parent_id : '',
					name : '',
					message : ''
				},
				comment_id: ''
			};
		},

		created() {
			this.fetchComments();
		},

		methods : {
			fetchComments() {
				fetch('comments')
					.then(res => res.json())
					.then(res => {
						this.comments = res;
						console.log(res);
					});
			},

			submitForm : function() {
				const pageToken = document.querySelector('head meta[name="csrf-token"]');

				console.log(pageToken['content']);
				console.log(this.name);
				console.log(this.message);

				var fields = { 
						parent_id:0, 
						name:this.name, 
						message:this.message, 
						_token:pageToken['content'] 
					};
				axios.post('store', fields)
					.then(res => { 
						this.name = '';
						this.message = '';
						this.fetchComments();
					})
					.catch(error  => {
						if (error.response.status === 422) {
							console.log(error.response);
						}
					});
			}
		}
	};
</script>