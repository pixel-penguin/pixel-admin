<template>
    <div>
		<div class="row">
			<div class="col-md-12">
				<h4 class="page-title">Pages Component </h4>
			</div>
            

            <div class="col-md-6" style="margin-bottom:20px">
                <div @click="tutorialVideoModal = true" class="btn btn-small btn-primary rounded" data-toggle="modal" data-target="#add_user"><i class="fa fa-video-camera" aria-hidden="true"></i> Tutorial</div>
            </div>
			<div class="col-md-6 text-right m-b-30">
				
                <div @click="addPage" class="btn btn-primary rounded" data-toggle="modal" data-target="#add_user"><i class="fa fa-plus"></i> Add Page</div>
			</div>
		</div>
		
		<!--
		<div class="row filter-row">
			<div class="col-sm-3 col-xs-6">
				<div class="form-group form-focus">
					<label class="control-label">Name</label>
					<input type="text" class="form-control floating">
				</div>
			</div>
			
		</div>
		-->
		<div class="row">
			<div class="col-md-12">
				
				<vue-nestable @change="saveOrder" v-model="pages">
                    <vue-nestable-handle
                    slot-scope="{ item }"
                    :item="item">

                    <strong class="nestText">
                    {{ item.name }}
                    </strong>

                    <strong class="nestAction">
                    
                        <a v-tooltip="'Edit Page'" :href="'/admin/pages/'+item.id+'/edit/'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        
                        <i @click="activate(item.id)" v-tooltip="'Published'" v-if="item.active == true" style="color:#2FC937" class="fa fa-check-square-o" aria-hidden="true"></i>
                        <i @click="activate(item.id)" v-tooltip="'Not Published'" v-if="item.active == false" style="color: #C51515" class="fa fa-square-o" aria-hidden="true"></i>
                        
                        <i @click="destroyPage(item.id)" v-tooltip="'Delete Page'" class="fa fa-trash" aria-hidden="true" style="color: #C51515"></i>
                    </strong>
                    </vue-nestable-handle>
                </vue-nestable>

                <!--<div @click="saveOrder"  class="btn btn-primary">Save Order</div>-->
				
			</div>
		</div>
        
        
        <simple-modal v-model="tutorialVideoModal" title="Tutorial">
            <template slot="body">                
                <iframe style="width:1280px; height:400px" width="1280" height="720" src="https://www.youtube.com/embed/c50rmy_h2iU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </template>
            <template slot="footer">
                <button>OK</button>
            </template>
        </simple-modal>
    </div>
</template>

<script>

import SimpleModal from 'simple-modal-vue'
    export default {
        name: 'tree-menu',
        data(){
            return{
                cloudinaryCloudName:null,

                request_source : '',

                nestableItems: [
                    {
                        id: 0,
                        name: 'Andy'
                    }, {
                        id: 1,
                        name: 'Harry',
                        children: [{
                            id: 2,
                            name: 'David'
                        }]
                    }, {
                        id: 3,
                        name: 'Lisa'
                    }
                    
                ],
                pages:[],

                tutorialVideoModal: false
            }
        },
        components: { SimpleModal },
        mounted() {
            const self = this;

            self.cloudinaryCloudName = process.env.MIX_CLOUDINARY_CLOUD_NAME;
            
            self.getAllPages();

        },
        methods:{
            
            getAllPages(){
                const self = this;

                axios.get('/json/pages/Y')
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    console.log(data);

                    self.pages = data.obj;
                    //this.info = response.data.bpi

                    //self.instNavigationAnimation();
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            addPage(){
                const self = this;

                self.$swal.fire({
                    title: 'Enter Page Name',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Add',
                    showLoaderOnConfirm: true,
                    preConfirm: (name) => {

                        axios.post('/admin/pages',{
                            name:name,
                        })
                        .then(response => {
                            this.$swal({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 3000,
                                type: 'success',
                                title: 'Page Added Successfully'
                            });

                            self.getAllPages();                       

                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                            `Request failed: ${error}`
                            )
                        })


                    },
                    allowOutsideClick: () => !Swal.isLoading()
                    })
            },
            destroyPage(pageId){
				const self = this;

				//console.log(rowId);
				
				this.$swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            axios.delete('/admin/pages/'+pageId).then(response => {
								self.getAllPages();

								this.$swal({
									toast: true,
									position: 'bottom-end',
									showConfirmButton: false,
									timer: 3000,
									type: 'success',
									title: 'Page Deleted successfully'
								})

                                self.getAllPages();
								//self.showAddForm = false;
							})
							.catch(e => {
								this.errors.push(e)
							})
                        }
                    })
			},
            saveOrder(){
                const self = this;

                if(self.request_source != ''){
					//console.log('cancelled');
					self.request_source.cancel('Operation canceled by the user.');
				}

                self.request_source = Vue.axios.CancelToken.source();

                axios.post('/admin/pages/order',{
                    cancelToken: self.request_source.token,
                    pages:self.pages,
                })
                .then(response => {
                   this.$swal({
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 3000,
                        type: 'success',
                        title: 'Order Saved successfully'
                    })
                })
                .catch(error => {
                    self.$swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                })
            },
            
            activate(id){
                const self = this;

                axios.post('/admin/pages/activate',{
                    id:id,
                })
                .then(response => {
                   this.$swal({
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 3000,
                        type: 'success',
                        title: 'Order Saved successfully'
                    });

                    self.getAllPages();
                })
                .catch(error => {
                    self.$swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                })
            }
            
        }

    }

</script>

<style src="vue-nestable/example/assets/vue-nestable.css"></style>