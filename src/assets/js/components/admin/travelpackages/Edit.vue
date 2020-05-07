<template>
    <div>
		<div class="row">

            <div class="col-md-12" style="margin-bottom:20px">
                <ul class="nav nav-tabs">
                    <li @click="step=1" v-bind:class="{ active: step == 1 }"><a href="#">Basic Detail</a></li>
                    <li v-if="travelPackage.id != 0" @click="step=2" v-bind:class="{ active: step == 2 }"><a href="#">Includes</a></li>
                    <li v-if="travelPackage.id != 0" @click="step=3" v-bind:class="{ active: step == 3 }"><a href="#">Detail</a></li>
                    <li v-if="travelPackage.id != 0" @click="step=4" v-bind:class="{ active: step == 4 }"><a href="#">Pricing</a></li>
                    <li v-if="travelPackage.id != 0" @click="step=5" v-bind:class="{ active: step == 5 }"><a href="#">Images</a></li>
                    <li v-if="travelPackage.id != 0" @click="step=6" v-bind:class="{ active: step == 6 }"><a href="#">Extra</a></li>
                </ul>
            </div>

            <div v-if="step == 1">
                <form @submit="updateTravelPackage">
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Name</label>
                            <input required class="form-control" type="text" v-model="travelPackage.name">
                        </div>
                    </div>
                    
                                        
                    <div class="col-md-6">

                        <div class="form-group">
                            <label>Type</label>

                            <select required class="form-control" type="text" v-model="travelPackage.travel_package_type_id">
                                <option value="1">Type</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">

                        <div class="form-group">
                            <label>Expire Date (end of price date)</label>
                            <datepicker :format="format" class="datePickerComponent" v-model="travelPackage.expire_date"></datepicker>
                        </div>
                    </div>
                   
                    
                   
                    <div class="col-md-12">
                        

                        <div class="m-t-20 text-center">
                            <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                        </div>
                        
                    </div>
                </form>
            </div>

            <div v-if="step == 2">
                <form @submit="updateTravelPackage">
                    
                    
                    <div class="col-md-12">
                        
                        <div class="row">
                            <div class="col-sm-2 col-md-2" v-for="color in travelPackage.colors" :key="color.id" style="text-align:center;margin-bottom:20px">
                                <label>{{color.name}}</label>
                                <i :style="'background:'+color.code+'; height:50px; width:50px; border-radius: 50%; display:block; margin:auto'"></i>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-12">

                        <div class="form-group">
                            <label>Includes</label>
                            <multiselect
                                v-model="travelPackage.includes"
                                :options="travelPackageIncludeOptions"
                                label="name" 
                                track-by="name"
                                multiple >
                            </multiselect>
                        </div>

                    </div>

                    <div class="col-md-12">

                        <div class="form-group">
                            <label>Excludes</label>

                            <multiselect
                                v-model="travelPackage.excludes"
                                :options="travelPackageExcludeOptions"
                                label="name" 
                                track-by="name"
                                multiple >
                            </multiselect>
                        </div>
                    </div>

                    <div class="col-md-12">

                        <div v-for="travelPackage in travelPackage.attributes" :key="travelPackage.id" class="form-group">
                            <label>{{travelPackage.name}}</label>

                            <tags-input element-id="value"
                            v-model="travelPackage.value"></tags-input>

                        </div>
                    </div>

                    <div class="col-md-12">
                        

                        <div class="m-t-20 text-center">
                            <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                        </div>
                        
                    </div>
                    
                </form>
            </div>

            <div v-if="step == 3">
                <form @submit="updateTravelPackage">
                    
                    <div class="col-md-12">

                        <div class="form-group">
                            <label>Content</label>

                            <div style="height:auto" @click="openEditor" class="form-control" v-html="travelPackage.detail" ></div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea class="form-control" v-model="travelPackage.includes" >
                            </textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        

                        <div class="m-t-20 text-center">
                            <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                        </div>
                        
                    </div>
                    
                </form>
            </div>

            <div v-if="step == 4">
                <form>
                    
                    
                    <div class="col-md-12">

                        <table class="table">
                            <thead>
                                <tr>
                                    <th colspan="2">30 June 2020</th>
                                    <th>10 July 2020</th>
                                    <th>
                                        <div class="btn btn-primary btn-xs"><i class="fa fa-money" aria-hidden="true"></i> Add Price</div>
                                        <i style="background:red; color:#FFF; padding:3px; border-radius:50%" class="fa fa-trash" aria-hidden="true"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Johannesburg</td>
                                    <td>R15 205</td>
                                    <td>per person sharing</td>
                                    <td><i style="background:red; color:#FFF; padding:3px; border-radius:50%" class="fa fa-trash" aria-hidden="true"></i></td>
                                </tr>
                                <tr>
                                    <td>Capetown</td>
                                    <td>R15 205</td>
                                    <td>per person sharing</td>
                                    <td><i style="background:red; color:#FFF; padding:3px; border-radius:50%" class="fa fa-trash" aria-hidden="true"></i></td>
                                </tr>

                                <tr>
                                    <td>Capetown</td>
                                    <td>R15 205</td>
                                    <td>per person sharing</td>
                                    <td><i style="background:red; color:#FFF; padding:3px; border-radius:50%" class="fa fa-trash" aria-hidden="true"></i></td>
                                </tr>
                            </tbody>
                        </table>

                        
                    </div>

                    <div class="col-md-12">
                        

                        <div>
                            <div @click="travelDateModal = true" class="btn btn-primary">Add Travel Date</div>
                        </div>
                        
                    </div>
                    
                </form>
            </div>

            <div v-if="step == 5">
                <div class="col-md-12">
                    
                    <div class="form-group" v-if="travelPackage.id != 0">
                        <label>Gallery</label>
                        <div>
                            <input class="form-control" type="file" v-on:change="onGalleryChange" multiple>
                            <small class="help-block">Max. file size: 10 MB. Allowed images: jpg, gif, png.</small>
                            <div style="margin-bottom:20px">
                                <div v-if="galleryLoading == false" @click="uploadGallery(0)" class="btn btn-primary btn-sm">Upload Images</div>
                                <!--<div v-if="galleryLoading == false" @click="saveGalleryOrder" class="btn btn-primary btn-sm">Save Gallery Order</div>-->
                                <div v-if="galleryLoading == true" class="btn btn-primary btn-sm"><i class='fa fa-circle-o-notch fa-spin'></i> Uploading</div>                            
                            </div>
                        </div>
                        <div class="row">
                            
                            <ul class="list-group"  v-sortable="{ onUpdate: onOrder }">
                                <li class="col-md-4 col-sm-2 col-xs-4 col-lg-2 list-group-item" v-for="item in travelPackageGallery" :key="item.id" >
                                    <div class="thumbnail product-thumbnail">
                                        <div class="thumb">
                                            <img :src="'https://res.cloudinary.com/'+cloudinaryCloudName+'/image/upload/c_fill,h_100,w_150/v1548624788/'+item.image_name+'.jpg'" class="img-responsive" alt="">
                                        </div>
                                        <span @click="destroyGalleryFile(item.id)" class="product-remove" title="remove"><i class="fa fa-close"></i></span>
                                    </div>
                                </li>
                            </ul>
                            
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="step == 6">
                <form @submit="updateTravelPackage">
                    
                    
                     <div class="col-md-12">

                        <div class="form-group">
                            <label>Featured</label>

                            <p>
                                <p-check class="p-switch" name="check" color="success" v-model="travelPackage.featured"></p-check>
                            </p>

                        </div>
                    </div>

                    <div class="col-md-12">

                        <div class="form-group">
                            <label>Published/Active</label>

                            <p>
                                <p-check class="p-switch" name="check" color="success" v-model="travelPackage.active"></p-check>
                            </p>

                        </div>
                    </div>                    
                    

                    <div class="col-md-12">
                        

                        <div class="m-t-20 text-center">
                            <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                        </div>
                        
                    </div>
                    
                </form>
            </div>
        </div>

        <simple-modal v-model="editContent" title="Edit">
            <template slot="body">
                <editor 
                v-if="screenHeight != null"
                v-model="travelPackage.detail"
                api-key="9tv5nzovtpredmv4b778na7pauhqz7n3rk4pviia6wla45v2" 
                :init="{
                    browser_spellcheck: true,
                    plugins: 'wordcount, image, media, link, table, lists, code', 
                    //menubar: 'insert',
                    toolbar: 'image, media, link, table, numlist bullist, code',
                    file_picker_types: 'file image media',
                    //extended_valid_elements: 'svg[*],defs[*],pattern[*],desc[*],metadata[*],g[*],mask[*],path[*],line[*],marker[*],rect[*],circle[*],ellipse[*],polygon[*],polyline[*],linearGradient[*],radialGradient[*],stop[*],image[*],view[*],text[*],textPath[*],title[*],tspan[*],glyph[*],symbol[*],switch[*],use[*]',
                    file_browser_callback_types: 'file image media',
                    automatic_uploads: true,
                    images_upload_url: '/admin/mcefileupload',
                    height: screenHeight - 200,        
                    
                    link_assume_external_targets: true,                               
                }"></editor>

                <div style="margin-top:10px; text-align:right">
                    <div @click="editContent = false" class="btn btn-primary">Apply</div>
                </div>
            </template>
            <template slot="footer">
                <button>OK</button>
            </template>
        </simple-modal>

        <simple-modal v-model="travelDateModal" title="Add Travel Date">
            <template slot="body">
                
                <div class="form-group">
                    <label>Start Date</label>
                    <datepicker :format="format" class="datePickerComponent" v-model="travelStartDate"></datepicker>
                </div>

                <div class="form-group">
                    <label>End Date</label>
                    <datepicker :format="format" class="datePickerComponent" v-model="travelEndDate"></datepicker>
                </div>


                <div style="margin-top:10px; text-align:right">
                    <div @click="addTravelDate" class="btn btn-primary">Add</div>
                </div>
            </template>
            <template slot="footer">
                <button>OK</button>
            </template>
        </simple-modal>

    </div>
</template>

<script>
    
    import Multiselect from 'vue-multiselect'
    import Editor from '@tinymce/tinymce-vue';
    import SimpleModal from 'simple-modal-vue'
    import Datepicker from 'vuejs-datepicker';

    export default {
        name: 'Edit-TravelPackage',
        data(){
            return{
                cloudinaryCloudName:null,
                step: 1,
                travelDateModal: false,

                loading: false,
                galleryLoading: false,
                previewImageUrl:null,
                travelPackage_id: 0,
                travelPackageGallery: [],
                travelPackage:{
                    id:0,
                    travel_package_type_id: null,
                    name: null,
                    banner_image_name: null,
                    description:null,
                    notes:null,
                    expire_date:null,
                    active: false,
                    is_featured: false
                },
                successSound:new Audio('https://res.cloudinary.com/dhmwdhirs/video/upload/v1558165617/audio/admin-sounds/bigbox.mp3'),

                travelPackageIncludeOptions:[],
                travelPackageExcludeOptions:[],
                tags: [],

                travelStartDate: null,
                travelEndDate:null,

                screenHeight: null,
                editContent: false,

                format: 'yyyy-MM-dd',
            }
        },
        props: ['travel_package_id_link'],
        components: { 
            Multiselect,
            'editor': Editor ,
            SimpleModal, 
            Datepicker 
        },
        mounted() {
            const self = this;

            self.cloudinaryCloudName = process.env.MIX_CLOUDINARY_CLOUD_NAME;

            self.travel_package_id = self.travel_package_id_link;
            
            if(self.travel_package_id > 0){
                self.travelPackageDetail();
            }

            
            self.getGalleryFiles();
            self.getTravelPackageCategories();

        },
        methods:{
            openEditor(){
                const self = this;

                self.screenHeight = window.innerHeight;
                $('html,body').scrollTop(0);
                
                //alert(self.screenHeight);

                setTimeout(function(){
                    self.editContent = true; 
                }, 200);
            },
            getTravelPackageCategories(){
                const self = this;

                axios.get('/json/travelpackagecategories/get')
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.travelPackageCategoryOptions = data.obj;

                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            onOrder: function (event) {
                const self = this;

                console.log('Ordering');
                this.travelPackageGallery.splice(event.newIndex, 0, this.travelPackageGallery.splice(event.oldIndex, 1)[0])

                self.saveGalleryOrder();
            },
            travelPackageDetail(){
                const self = this;

                axios.get('/json/travelpackage/detail/'+self.travel_package_id)
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.travelPackage = data.obj;

                    self.travelPackage.date_created = new Date(self.travelPackage.date_created);

                    self.travel_package_id = self.travelPackage.id;

                    self.travelPackageIncludeOptions = data.includes;
                    self.travelPackageExcludeOptions = data.excludes;
                    

                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },

            addTravelDate(e){
                const self = this;

                e.preventDefault();
        
                axios.post('/admin/travelpackages/addtraveldate',{

                    id: self.travelPackage.id,
                    travelStartDate: self.travelStartDate,
                    travelEndDate: self.travelEndDate,
                    
                   
                })
                .then(response => {
                    
                    var data = response.data;

                    self.travelStartDate = null;
                    self.travelEndDate = null;

                    self.travelPackageDetail();
                    this.$swal({
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 3000,
                        type: 'success',
                        title: 'Added Travel Date',
                        onOpen: function() {
                            var zippi = new Audio('https://res.cloudinary.com/dhmwdhirs/video/upload/v1558165617/audio/admin-sounds/bigbox.mp3')
                            zippi.play();
                        }
                    });                    
                    
                })
                .catch(error => {
                    Swal.showValidationMessage(
                    `Request failed: ${error}`
                    )
                })

            }, 
            updateTravelPackage(e){
                const self = this;

                e.preventDefault();
        
                axios.patch('/admin/travelpackages/'+self.travel_package_id,{

                    data: self.travelPackage,

                    /*
                    id:0,

                    //step 1
                    travel_package_type_id: self.travelPackage.travel_package_type_id,
                    name: self.travelPackage.name,
                    expire_date:self.travelPackage.expire_date,

                    //step2
                    
                    banner_image_name: self.travelPackage.banner_image_name,
                    description:self.travelPackage.description,
                    notes:self.travelPackage.notes,
                    
                    active: self.travelPackage.active,
                    is_featured: self.travelPackage.is_featured
                    
                    /*
                    name: self.travelPackage.name,
                    tags: self.travelPackage.tags,
                    active: self.travelPackage.active,
                    detail: self.travelPackage.detail,
                    date_created: self.travelPackage.date_created.toISOString().slice(0, 19).replace('T', ' '),
                    url: self.travelPackage.url,
                    travelPackage_category: self.travelPackageCategories,
                    detail_summary: self.travelPackage.detail_summary
                    */
                })
                .then(response => {
                    
                    var data = response.data;

                    self.travel_package_id = data.obj.id;

                    self.travelPackageDetail();
                    this.$swal({
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 3000,
                        type: 'success',
                        title: 'Updated Page Successfully',
                        onOpen: function() {
                            var zippi = new Audio('https://res.cloudinary.com/dhmwdhirs/video/upload/v1558165617/audio/admin-sounds/bigbox.mp3')
                            zippi.play();
                        }
                    });
                    //self.successSound.play();
                    self.getGalleryFiles();
                })
                .catch(error => {
                    Swal.showValidationMessage(
                    `Request failed: ${error}`
                    )
                })

            },  
            
            saveGalleryOrder(){
                const self = this;

                console.log(self.travelPackageGallery);

                axios.post('/admin/travelpackages/gallery/order/'+self.travel_package_id,{
                    travelPackageGallery:self.travelPackageGallery,
                })
                .then(response => {
                    /*
                    if (!response.ok) {
                    throw new Error(response.statusText)
                    }
                    return response.json()
                    */
                   this.$swal({
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 3000,
                        type: 'success',
                        title: 'Ordered Successfully'
                    });
                    
                    self.successSound.play();

                })
                .catch(error => {
                    self.$swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                })
            },
            uploadGallery(count = 0){
                const self = this;
                
                //console.log(count);
                //console.log(self.galleryFiles.length);

                self.galleryLoading = true;

                if(count < self.galleryFiles.length){
                    
                    //console.log(self.galleryFiles)
                    //console.log(self.galleryFiles[count])

                    var formData = new FormData();
                    formData.append('travel_package_id', self.travel_package_id);
                    formData.append('image_name', self.galleryFiles[count]); 

                    axios.post('/admin/travelpackages/gallery/upload', formData,formData,
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    
                    })
                    .then(response => {
                        count++;
                        self.uploadGallery(count);
                    })
                    .catch(error => {
                        self.$swal.showValidationMessage(
                            `Request failed: ${error}`
                        )

                        alert('Something went wrong with uploads');
                        
                        self.galleryLoading = false;
                    })
                }
                else{
                    //console.log(count);
                    //console.log('done');

                    self.galleryLoading = false;

                    self.getGalleryFiles();
                }
                
                //console.log(self.galleryFiles);
            },
            onGalleryChange(e) {
                const self = this;
                
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                self.galleryFiles = files;
            },
            getGalleryFiles(){
                const self = this;

                axios.get('/json/travelpackage/gallery/'+self.travel_package_id)
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.travelPackageGallery = data.obj;
                    
                })
                .catch(error => {
                    self.$swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                })
            },
            destroyGalleryFile(galleryId){
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
                            axios.delete('/admin/travelpackages/gallery/delete/'+galleryId).then(response => {
								
                                self.getGalleryFiles();

								this.$swal({
									toast: true,
									position: 'bottom-end',
									showConfirmButton: false,
									timer: 3000,
									type: 'success',
									title: 'Image Deleted successfully'
								})
                                self.successSound.play();

								//self.showAddForm = false;
							})
							.catch(e => {
								this.errors.push(e)
							})
                        }
                    })
			},
            
        }

    }

</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style src="@voerro/vue-tagsinput/dist/style.css"></style>

