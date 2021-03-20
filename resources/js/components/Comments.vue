<template>
	<div>
		<h2>Comments</h2>
        <div class="row">
            <div class="col-sm-4">
                <form class="comment-form" @submit.prevent="submitForm(0)">
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

		<div class="row">
			<div class="col-sm-12" v-for="(comment, key) in comments" v-bind:key="comment.id">
				<div class="card card-body">
					<figure>
						<strong>{{ comment.name }}</strong>
						<blockquote class="blockquote">
							<p>{{ comment.message }}</p>
						</blockquote>
						<figcaption class="blockquote-footer">
							{{ comment.created_at }}
						</figcaption>
						
						<a class="btn btn-primary btn-sm" :href="'#collapseExample' + comment.id" v-on:click="toggle(key)">Reply</a>

						<div class="collapse" :class="{show:comment.isShow}" :id="'collapseExample' + comment.id">
			                <form class="comment-form" @submit.prevent="submitForm(comment.id)">
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
					</figure>
				</div>

				<div class="row">
					<div class="col-sm-11 offset-sm-1" v-for="(sub, sub_key) in comment.sub_comments">
						<div class="card card-body">
							<figure>
								<strong>{{ sub.name }}</strong>
								<blockquote class="blockquote">
									<p>{{ sub.message }}</p>
								</blockquote>
								<figcaption class="blockquote-footer">
									{{ sub.created_at }}
								</figcaption>

								<a class="btn btn-primary btn-sm" :href="'#collapseExample' + sub.id"  v-on:click="toggle_sub(sub_key)">Reply</a>

								<div class="collapse" ref="collapseExample" :class="{show:comment.sub_comments.isShow}" :id="'collapseExample_' + key">
					                <form class="comment-form" @submit.prevent="submitForm(sub.id)">
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
							</figure>
								
						</div>

						<div class="row">
							<div class="col-sm-10 offset-sm-1" v-for="sub_2 in sub.sub_comments">
								<div class="card card-body">
									<figure>
										<strong>{{ sub_2.name }}</strong>
										<blockquote class="blockquote">
											<p>{{ sub_2.message }}</p>
										</blockquote>
										<figcaption class="blockquote-footer">
											{{ sub_2.created_at }}
										</figcaption>
									</figure>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				name : '',
				message : '',

				isShow: false,

				comments : [],
				comment : {
					id : '',
					parent_id : '',
					name : '',
					message : '',
					sub_comments : []
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

			replyClick : function(id) {
				this.users.map(user => {
					if(id = comment_form.id) {
						comment_form.visible = !!! comment_form.visible
					}
				})
			},


            toggle(key) {
              	var vm = this;

            		console.log(vm.comments[key]);

              	if (!vm.comments[key].isShow) {
                	vm.comments[key].isShow = true;
              	} else  {
                  	vm.comments[key].isShow = false;
              	}
            },

            toggle_sub(sub_key) {
              	var vm = this;
              	var id = this.$refs.collapseExample[sub_key].id;
              	var id_exp = this.$refs.collapseExample[sub_key].id.split('_');
              	var key = parseInt(id_exp[1]);

              	if (!vm.comments[key].sub_comments[sub_key].isShow) {
              		$('#' + id).addClass('show');
                	vm.comments[key].sub_comments[sub_key].isShow = true;
              	} else  {
              		$('#' + id).removeClass('show');
                  	vm.comments[key].sub_comments[sub_key].isShow = false;
              	}
            },

			submitForm : function(parent_id) {
				const pageToken = document.querySelector('head meta[name="csrf-token"]');

				var fields = { 
						parent_id:parent_id, 
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
						if (error.response.status == 422) {
							var err_msg = "";
							$.each(error.response.data.errors, function(key, value){
								err_msg += "" + value[0] + "\n";
							});
							alert(err_msg);
						}
					});
			}
		}
	};
</script>