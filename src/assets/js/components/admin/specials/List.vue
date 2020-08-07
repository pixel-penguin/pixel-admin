<template>
    <div>
		<div class="row">
			<div class="col-md-12">
				<h4 class="page-title">Specials</h4>
			</div>
			<div class="col-md-12 text-right m-b-30">
				<a href="/admin/specials/create" class="btn btn-primary rounded"><i class="fa fa-plus"></i> Add Special</a>
			</div>
		</div>
		
		<div>
            <div class="ui container">
                <section>
                    <!--<input style="border:1px solid #CCC; padding:3px 10px; border-radius:10px; margin-bottom:10px" type="text" v-model="searchKey" placeholder="Filter Grab Voucher Here" />-->
                    
                    <v-client-table :columns="columns" v-model="specials" :options="options">
                        
                        <!--<a slot="name" slot-scope="props" target="_blank" :href="props.row.name" class="glyphicon glyphicon-eye-open"></a>-->

                        <div slot="name" slot-scope="props">
                            <p>{{props.row.name}}</p>
                        </div>

                        <div slot="actions" slot-scope="props">
                            <a :href="'/admin/specials/'+props.row.id+'/edit'" data-original-title="null" class=" has-tooltip">
                                <i v-tooltip.top-center="'Edit this Special'" aria-hidden="true" class="fa fa-pencil-square-o" style="color: rgb(45, 150, 60);"></i>
                            </a> 
                            
                            <i @click="activate(props.row.id)" v-tooltip="'Published'" v-if="props.row.active == true" style="color:#2FC937; cursor:pointer" class="fa fa-check-square-o" aria-hidden="true"></i>
                            <i @click="activate(props.row.id)" v-tooltip="'Not Published'" v-if="props.row.active == false" style="color: #C51515; cursor:pointer" class="fa fa-square-o" aria-hidden="true"></i>
                        

                            <i @click="destroyBlog(props.row.id)" v-tooltip.top-center="'Delete this Special'" style="color:#CB2225; cursor:pointer" class="fa fa-trash-o" aria-hidden="true"></i>
                        </div>

                        

                    </v-client-table>

                </section>
            </div>
        </div>
        
        

    </div>
</template>

<script>
    export default {
        name: 'Specials',
        data(){
            return{
                cloudinaryCloudName: null,

                //table
                columns:['name', 'actions'],
                options: {
                    headings: {
                        name: 'Name',
                        actions: 'Actions'
                    },
                    //editableColumns:['name'],
                    sortable: ['name'],
                    filterable: ['name']
                },

                specials:[],

                //end table

                linkCopied:null,

					
				
				isPaginated: true,
				isPaginationSimple: false,
				defaultSortDirection: 'asc',
				currentPage: 1,
				perPage: 10,
				showDetailIcon:true,
				searchKey:''                
                
            }
        },
        mounted() {
            const self = this;

            self.getAllSpecials();

           
            self.cloudinaryCloudName = process.env.MIX_CLOUDINARY_CLOUD_NAME;
            

            console.log('now');
            console.log(self.cloudinaryCloudName);
        },
        watch: {
            // whenever question changes, this function will run
            searchKey: function (newKeyword, oldKeyword) {
                const self = this

                //self.loyalties = self.filter(self.loyaltiesOriginal, "name", newKeyword);
                self.filter();
                //console.log(newKeyword)
                //this.answer = 'Waiting for you to stop typing...'
                //this.debouncedGetAnswer()
            },
        },
        methods:{
            
            getAllSpecials(){
                const self = this;

                axios.get('/json/specials/1')
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    console.log(data);

                    self.specials = data.obj;
                    self.testimonitalsOriginal = data.obj;
                    //this.info = response.data.bpi

                    //self.instNavigationAnimation();
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            filter(){
				const self = this;


				var i, j, hash = [], item;

				for(i =  0, j = self.testimonitalsOriginal.length; i<j; i++){
					item = self.testimonitalsOriginal[i];
					if(typeof item['name'] !== "undefined" && item['name'].includes(self.searchKey)){
						hash.push(item);
					}
				}

				//console.log(hash);
				self.specials = hash;
				//return hash;
			},
            destroySpecial(specialId){
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
                            axios.delete('/admin/specials/'+specialId).then(response => {
								self.getAllSpecials();

								this.$swal({
									toast: true,
									position: 'bottom-end',
									showConfirmButton: false,
									timer: 3000,
									type: 'success',
									title: 'Special Deleted successfully'
								})

                                self.getAllSpecials();
								//self.showAddForm = false;
							})
							.catch(e => {
								this.errors.push(e)
							})
                        }
                    })
			},            
            activate(id){
                const self = this;

                //console.log(id);

                axios.post('/admin/specials/activate',{
                    id:id,
                })
                .then(response => {
                   this.$swal({
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 3000,
                        type: 'success',
                        title: 'Status updated successfully'
                    });

                    self.getAllSpecials();
                })
                .catch(error => {
                    self.$swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                    self.getAllSpecials();
                })
            }
            
        }

    }

</script>
