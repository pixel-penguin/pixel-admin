<template>
    <div>
		<div class="row">
			<div class="col-xs-4">
				<h4 class="page-title">Projects</h4>
			</div>
			<div class="col-xs-8 text-right m-b-30">
				<a href="/admin/projects/create" class="btn btn-primary rounded"><i class="fa fa-plus"></i> Add Project</a>
			</div>
		</div>
		
		<div>
            <div class="ui container">
                <section>
                    <!--<input style="border:1px solid #CCC; padding:3px 10px; border-radius:10px; margin-bottom:10px" type="text" v-model="searchKey" placeholder="Filter Grab Voucher Here" />-->
                    <b-table
                        :data="projects"
                        :paginated="isPaginated"
                        :per-page="perPage"
                        :current-page.sync="currentPage"
                        :pagination-simple="isPaginationSimple"
                        :default-sort-direction="defaultSortDirection"
                        default-sort="name"
                        detailed
                        detail-key="id"
                        
                        :show-detail-icon="showDetailIcon"					
                        >

                        <template slot-scope="props" visible="props.row.name.includes(searchKey) == true">
                            <b-table-column field="id" label="ID" width="40" sortable numeric>
                                {{ props.row.id }}
                            </b-table-column>

                            <b-table-column field="name" label="Name" sortable>
                                <strong style="font-weight:normal">{{ props.row.name }}</strong>
                            </b-table-column>

                            <b-table-column field="date" label="Created At" sortable centered>							
                                {{ new Date(props.row.created_at).toLocaleDateString() }}							
                            </b-table-column>

                            <b-table-column label="Status">
                                <div v-if="props.row.active == false" class="statusTag" style="background:#CB2225">inActive</div>
                                <div v-if="props.row.active == true" class="statusTag" style="background:#2D963C">Active</div>
                            </b-table-column>

                            <b-table-column label="Action">
                                <!--<a target="_blank" :href="'https://buddy.na/page/'+props.row.id"><i v-tooltip.top-center="'View Page'" style="color:#2D963C" class="fa fa-eye" aria-hidden="true"></i></a>-->
                                <a v-tooltip.top-center="'Edit Project Content'" :href="'/admin/projects/'+props.row.id+'/edit'"><i style="color:#2D963C" class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                <!--<a :href="'/grabvoucher/addons/'+props.row.id" v-tooltip.top-center="'Edit Add-Ons like Audio Files and Keywords'"><i style="color:#2D963C" class="fa fa-plus-circle" aria-hidden="true"></i></a>-->

                                <i @click="activate(props.row.id)" v-tooltip="'Published'" v-if="props.row.active == true" style="color:#2FC937; cursor:pointer" class="fa fa-check-square-o" aria-hidden="true"></i>
                                <i @click="activate(props.row.id)" v-tooltip="'Not Published'" v-if="props.row.active == false" style="color: #C51515; cursor:pointer" class="fa fa-square-o" aria-hidden="true"></i>
                        

                                <i @click="destroyProject(props.row.id)" v-tooltip.top-center="'Delete this Project'" style="color:#CB2225; cursor:pointer" class="fa fa-trash-o" aria-hidden="true"></i>
                            </b-table-column>
                        </template>

                        <template slot="detail" slot-scope="props" visible="props.row.name.includes(searchKey) == true" >
                            <article class="media">
                                <div class="media-content">
                                   
                                    <div class="content">
                                        <div style="float:left; margin-right:20px">
                                            <img v-if="props.row.image_name != null && props.row.image_name.length > 3" :src="'https://res.cloudinary.com/'+cloudinaryCloudName+'/image/upload/c_fill,h_200,w_200/v1551874928/'+props.row.image_name+'.jpg'" />
                                            <img v-else :src="'https://res.cloudinary.com/buddy-industries-cc/image/upload/c_fill,h_200,w_200/v1551874928/placeholder/placeholder.jpg'" />
                                        </div>
                                        <div style="float:left; line-height:200px" >

                                            
                                            <p-check  v-model="props.row.active" v-on:change="activate(props.row.id)" class="p-icon p-plain" toggle>
                                                <i class="icon mdi mdi-eye" slot="extra"></i>
                                                Published
                                                <i class="icon mdi mdi-eye-off" slot="off-extra"></i>
                                                <label slot="off-label">Unpublished</label>
                                            </p-check>
                                        </div>

                                        <!--
                                        <div style="float:right; line-height:200px">
                                            <div @click="showQrCode(props.row.id)" class="btn btn-default"><i class="fa fa-qrcode"></i> View QR Code</div>
                                            <div @click="showLinkCode(props.row.id)" class="btn btn-default"><i class="fa fa-link"></i> View Direct Link</div>

                                            <div v-clipboard:copy="'https://buddy.na/page/'+props.row.id"
                                                v-clipboard:success="linkCopied"											
                                                class="btn btn-default"><i class="fa fa-link"></i> Copy Direct Link

                                            </div>
                                            
                                        </div>
                                        -->

                                    </div>
                                   
                                </div>
                            </article>
                        </template>
                    </b-table>
                </section>
            </div>
        </div>
        
        

    </div>
</template>

<script>
    export default {
        name: 'Projects',
        data(){
            return{
                cloudinaryCloudName: null,

                linkCopied:null,

				testimonitalsOriginal:[],		
				projects:[],					
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

            self.getAllProjects();

           
            self.cloudinaryCloudName = process.env.MIX_CLOUDINARY_CLOUD_NAME;


            //alert(self.cloudinaryCloudName;)
            

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
            
            getAllProjects(){
                const self = this;

                axios.get('/json/projects/1')
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    console.log(data);

                    self.projects = data.obj;
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
				self.projects = hash;
				//return hash;
			},
            destroyProject(projectId){
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
                            axios.delete('/admin/projects/'+projectId).then(response => {
								self.getAllProjects();

								this.$swal({
									toast: true,
									position: 'bottom-end',
									showConfirmButton: false,
									timer: 3000,
									type: 'success',
									title: 'Project Deleted successfully'
								})

                                self.getAllProjects();
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

                axios.post('/admin/projects/activate',{
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

                    self.getAllProjects();
                })
                .catch(error => {
                    self.$swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                    self.getAllProjects();
                })
            }
            
        }

    }

</script>
