<template>
    <div>
		<div class="row">
			<div class="col-md-12">
				<h4 class="page-title">Product Categories </h4>
                
                
			</div>
             <div class="col-md-6" style="margin-bottom:20px">
                        <div @click="tutorialVideoModal = true" class="btn btn-small btn-primary rounded" data-toggle="modal" data-target="#add_user"><i class="fa fa-video-camera" aria-hidden="true"></i> Tutorial</div>
                    </div>
			<div class="col-md-6 text-right m-b-30">
				
                <div @click="addProductCategory" class="btn btn-primary rounded" data-toggle="modal" data-target="#add_user"><i class="fa fa-plus"></i> Add Category</div>
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
				
				<vue-nestable @change="saveOrder" v-model="productCategories" :maxDepth="categoryMaxLevel">
                    <vue-nestable-handle
                    slot-scope="{ item }"
                    :item="item"                   
                    >

                    <strong class="nestText">
                    {{ item.name }}
                    </strong>

                    <strong class="nestAction">
                    
                        <a v-tooltip="'Edit Product Category'" :href="'/admin/productcategories/'+item.id+'/edit/'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        
                        <i @click="activate(item.id)" v-tooltip="'Published'" v-if="item.active == true" style="color:#2FC937" class="fa fa-check-square-o" aria-hidden="true"></i>
                        <i @click="activate(item.id)" v-tooltip="'Not Published'" v-if="item.active == false" style="color: #C51515" class="fa fa-square-o" aria-hidden="true"></i>
                        
                        <i @click="destroyProductCategory(item.id)" v-tooltip="'Delete Product Category'" class="fa fa-trash" aria-hidden="true" style="color: #C51515"></i>
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
                categoryMaxLevel:0,

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
                productCategories:[],

                tutorialVideoModal: false
            }
        },
        components: { SimpleModal },
        mounted() {
            const self = this;

            self.cloudinaryCloudName = process.env.MIX_CLOUDINARY_CLOUD_NAME;
            self.categoryMaxLevel = process.env.MIX_CATEGORY_MAX_LEVEL;
            
            //alert(self.categoryMaxLevel);

            self.getAllProductCategories();

        },
        methods:{
            
            getAllProductCategories(){
                const self = this;

                axios.get('/json/productcategories/Y/1')
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    console.log(data);

                    self.productCategories = data.obj;
                    //this.info = response.data.bpi

                    //self.instNavigationAnimation();
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            addProductCategory(){
                const self = this;

                self.$swal.fire({
                    title: 'Enter Product Category Name',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Add',
                    showLoaderOnConfirm: true,
                    preConfirm: (name) => {

                        axios.post('/admin/productcategories',{
                            name:name,
                        })
                        .then(response => {
                            this.$swal({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 3000,
                                type: 'success',
                                title: 'Product Category Added Successfully'
                            });

                            self.getAllProductCategories();                       

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
            destroyProductCategory(productCategoryId){
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
                            axios.delete('/admin/productcategories/'+productCategoryId).then(response => {
								self.getAllProductCategories();

								this.$swal({
									toast: true,
									position: 'bottom-end',
									showConfirmButton: false,
									timer: 3000,
									type: 'success',
									title: 'Product Category Deleted successfully'
								})

                                self.getAllProductCategories();
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

                axios.post('/admin/productcategories/order',{
                    cancelToken: self.request_source.token,
                    productCategories:self.productCategories,
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

                axios.post('/admin/productcategories/activate',{
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

                    self.getAllProductCategories();
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