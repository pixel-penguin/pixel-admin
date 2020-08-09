<template>
    <div>
		<div class="row">
			<div class="col-md-12">
				<h4 class="page-title">Travel Packages</h4>
			</div>
			<div class="col-md-12 text-right m-b-30">
				<a href="/admin/travelpackages/create" class="btn btn-primary rounded"><i class="fa fa-plus"></i> Add Travel Package</a>
			</div>
		</div>
		
		<div class="card">
            <div style="padding: 10px">
                <section>
                    <!--<input style="border:1px solid #CCC; padding:3px 10px; border-radius:10px; margin-bottom:10px" type="text" v-model="searchKey" placeholder="Filter Grab Voucher Here" />-->
                    <v-client-table :columns="columns" v-model="travelPackages" :options="options">
                        
                        <!--<a slot="name" slot-scope="props" target="_blank" :href="props.row.name" class="glyphicon glyphicon-eye-open"></a>-->

                        <div slot="name" slot-scope="props">
                            <p>{{props.row.name}}</p>
                        </div>

                        <div slot="actions" slot-scope="props">
                            <a :href="'/admin/travelpackages/'+props.row.id+'/edit'" data-original-title="null" class=" has-tooltip">
                                <i v-tooltip.top-center="'Edit this Calendar'" aria-hidden="true" class="fa fa-pencil-square-o" style="color: rgb(45, 150, 60);"></i>
                            </a> 
                            
                            <i @click="activate(props.row.id)" v-tooltip="'Published'" v-if="props.row.active == true" style="color:#2FC937" class="fa fa-check-square-o" aria-hidden="true"></i>
                            <i @click="activate(props.row.id)" v-tooltip="'Not Published'" v-if="props.row.active == false" style="color: #C51515" class="fa fa-square-o" aria-hidden="true"></i>
                        

                            <i @click="destroyBlog(props.row.id)" v-tooltip.top-center="'Delete this Calendar'" style="color:#CB2225; cursor:pointer" class="fa fa-trash-o" aria-hidden="true"></i>
                        </div>

                        

                    </v-client-table>
                </section>
            </div>
        </div>
        
        

    </div>
</template>

<script>
    export default {
        name: 'TravelPackages',
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
                travelPackages:[],	
                //end table

                linkCopied:null,

				testimonitalsOriginal:[],		
				travelPackages:[],					
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

            self.getAllTravelPackages();

           
            self.cloudinaryCloudName = process.env.MIX_CLOUDINARY_CLOUD_NAME;


            //alert(self.cloudinaryCloudName;)
            

            console.log('now');
            console.log(self.cloudinaryCloudName);
        },
        methods:{
            
            getAllTravelPackages(){
                const self = this;

                axios.get('/json/travelpackages/1')
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    console.log(data);

                    self.travelPackages = data.obj;
                    self.testimonitalsOriginal = data.obj;
                    //this.info = response.data.bpi

                    //self.instNavigationAnimation();
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            destroyTravelPackage(travelPackageId){
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
                            axios.delete('/admin/travelpackages/'+travelPackageId).then(response => {
								self.getAllTravelPackages();

								this.$swal({
									toast: true,
									position: 'bottom-end',
									showConfirmButton: false,
									timer: 3000,
									type: 'success',
									title: 'Travel Package Deleted successfully'
								})

                                self.getAllTravelPackages();
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

                axios.post('/admin/travelpackages/activate',{
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

                    self.getAllTravelPackages();
                })
                .catch(error => {
                    self.$swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                    self.getAllTravelPackages();
                })
            }
            
        }

    }

</script>
