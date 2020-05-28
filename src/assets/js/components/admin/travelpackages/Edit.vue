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
                    <li v-if="travelPackage.id != 0" @click="step=7" v-bind:class="{ active: step == 7 }"><a href="#">Itineraries</a></li>
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
                                <option v-for="type in types" :key="type.id" :value="type.id">{{type.name}}</option>
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
                                :taggable="true"
                                label="name" 
                                track-by="name"
                                @tag="addIncludeTag"
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
                                :taggable="true"
                                @tag="addExcludeTag"
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
                            <textarea class="form-control" v-model="travelPackage.notes" >
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

                        <table class="table" v-for="travelDate in travelPackage.travel_dates" :key="travelDate.id">
                            <thead>
                                <tr>
                                    <th colspan="2">{{ travelDate.start_date }}</th>
                                    <th>{{ travelDate.end_date }}</th>
                                    <th>
                                        <div @click="openTravelPackageTravelDatePriceModal(travelDate.id)" class="btn btn-primary btn-xs"><i class="fa fa-money" aria-hidden="true"></i> Add Price</div>
                                        <i @click="deleteTravelDate(travelDate.id)" style="background:red; color:#FFF; padding:3px; border-radius:50%" class="fa fa-trash" aria-hidden="true"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="price in travelDate.prices" :key="price.id">
                                    <td>{{ price.name }}</td>
                                    <td>R{{price.price}}</td>
                                    <td>{{price.type}}</td>
                                    <td><i @click="deleteTravelDatePrice(price.id)" style="background:red; color:#FFF; padding:3px; border-radius:50%" class="fa fa-trash" aria-hidden="true"></i></td>
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
                                            <img style="width:100%" :src="'https://res.cloudinary.com/'+cloudinaryCloudName+'/image/upload/c_fill,h_200,w_300/v1548624788/'+item.image_name+'.jpg'" class="img-responsive" alt="">
                                        </div>
                                        
                                        <input class="form-control" type="text" v-model="item.name">

                                        <div @click="updateImageName(item.id, item.name)" style="width:100%" class="btn btn-primary">Update</div>

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
                                <p-check class="p-switch" name="check" color="success" v-model="travelPackage.is_featured"></p-check>
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

            <div v-if="step == 7">
                    
                    
                <div class="col-md-12">
                    <ul class="list-group" v-sortable="{ handle: '.handle', onUpdate: onUpdate }">
                    
                    
                        <li v-for="itinerary in travelPackage.itineraries"  v-bind:key="itinerary.id" class="list-group-item">
                            
                            <div class="row">
                                
                               <div class="col-xs-10 col-sm-10 col-md-10">

                                    <div class="form-group">
                                        <label>Name</label>

                                        <input required class="form-control" type="text" v-model="itinerary.name">
                                    </div>

                                    <div class="form-group">
                                        <label>Day</label>

                                        <input required class="form-control" type="text" v-model="itinerary.day">
                                    </div>

                                    <div class="form-group">
                                        <label>Location</label>

                                        <input required class="form-control" type="text" v-model="itinerary.location">
                                    </div>

                                    <div class="form-group">
                                        <label>Description</label>

                                        <editor 
                                        v-model="itinerary.description"
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
                                            height: 200,        
                                            
                                            link_assume_external_targets: true,                               
                                        }"></editor>
                                    </div>
                                </div>

                                <div class="col-xs-2 col-sm-2 col-md-2">
                                    <i class="fa fa-arrows handle" aria-hidden="true"></i>
                                    <i @click="deleteItinerary(itinerary.id)" v-tooltip="'Delete Content Section'" class="fa fa-trash" aria-hidden="true"  style="color: #C51515"></i>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div @click="updateItineraryEntry(itinerary)" class="btn btn-primary">Update</div>
                                </div>
                            </div>

                            
                            
                        </li>
                        
                    </ul>                    
                </div>
                <div class="col-md-12">
                    <div @click="addItineraryEntry" class="btn btn-primary">Add itinerary Entry</div>
                </div>

               
                    
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

        <simple-modal v-model="travelDatePriceModal" title="Add Travel Date Price">
            <template slot="body">
                
                <div class="form-group">
                    <label>Name</label>
                    <input required class="form-control" type="text" v-model="travelPackagePriceName">
                </div>

                <div class="form-group">
                    <label>Price</label>
                    <input required class="form-control" type="number" v-model="travelPackagePricePrice">
                </div>

                <div class="form-group">
                    <label>Type</label>
                    <input required class="form-control" type="text" v-model="travelPackagePriceType">
                </div>


                <div style="margin-top:10px; text-align:right">
                    <div @click="addTravelDatePrice" class="btn btn-primary">Add</div>
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
                types:[],
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

                travelDatePriceModal: false,
                travelPackagePriceId: 0,
                travelPackagePriceName: null,
                travelPackagePricePrice: 0,
                travelPackagePriceType: null,

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
            
            /*
            if(self.travel_package_id > 0){
                self.travelPackageDetail();
            }
            */
            self.travelPackageDetail();
            
            self.getGalleryFiles();
            self.getTravelPackageCategories();

        },
        methods:{
            openTravelPackageTravelDatePriceModal(id){
                const self = this;

                self.travelDatePriceModal = true;

                self.travelPackagePriceId = id;
                
            },
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
                    
                    if(self.travel_package_id > 0){
                        self.travelPackage = data.obj;
                    }
                    
                    

                    self.travelPackage.date_created = new Date(self.travelPackage.date_created);

                    //self.travel_package_id = self.travelPackage.id;

                    self.travelPackageIncludeOptions = data.includes;
                    self.travelPackageExcludeOptions = data.excludes;
                    self.types = data.types;
                    

                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            addItineraryEntry(e){
                const self = this;

                e.preventDefault();
        
                axios.post('/admin/travelpackages/additinerary',{

                    travel_package_id: self.travelPackage.id                    
                   
                })
                .then(response => {
                    
                    var data = response.data;

                    //self.travelStartDate = null;
                    //self.travelEndDate = null;

                    self.travelPackageDetail();
                    this.$swal({
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 3000,
                        type: 'success',
                        title: 'Added Itinerary Entry',
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
            updateItineraryEntry(itineraryEntry){
                const self = this;

                //e.preventDefault();
        
                axios.post('/admin/travelpackages/updateitinerary',{

                    id: itineraryEntry.id,
                    name: itineraryEntry.name,
                    day: itineraryEntry.day,
                    location: itineraryEntry.location,
                    description: itineraryEntry.description,

                   
                })
                .then(response => {
                    
                    var data = response.data;

                    //self.travelStartDate = null;
                    //self.travelEndDate = null;

                    self.travelPackageDetail();
                    this.$swal({
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 3000,
                        type: 'success',
                        title: 'Updated Itinerary Entry',
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
            addTravelDatePrice(e){
                const self = this;

                e.preventDefault();
        
                axios.post('/admin/travelpackages/addtraveldateprice',{

                    travelPackagePriceId: self.travelPackagePriceId,
                    travelPackagePriceName: self.travelPackagePriceName,
                    travelPackagePricePrice: self.travelPackagePricePrice,
                    travelPackagePriceType: self.travelPackagePriceType,
                    
                   
                })
                .then(response => {
                    
                    var data = response.data;

                    self.travelPackagePriceId = 0,
                    self.travelPackagePriceName = null,
                    self. travelPackagePricePrice = 0,
                    self.travelPackagePriceType = null,

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
								
                                self.travelPackageDetail();

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
            
            onUpdate: function (event) {
                //this.list.splice(event.newIndex, 0, this.list.splice(event.oldIndex, 1)[0])
                const self = this;
                self.travelPackage.itineraries.splice(event.newIndex, 0, self.travelPackage.itineraries.splice(event.oldIndex, 1)[0])
                //console.log(self.pageContents);

                axios.post('/admin/travelpackages/itinerary/order',{
                    content:self.travelPackage.itineraries,
                    //content: 
                })
                .then(response => {
                    /*
                    if (!response.ok) {
                    throw new Error(response.statusText)
                    }
                    return response.json()
                    */
                })
                .catch(error => {
                    self.$swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                })
            },
            deleteTravelDate(id){
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
                            axios.delete('/admin/travelpackages/traveldates/delete/'+id).then(response => {
								
                                self.travelPackageDetail();

								this.$swal({
									toast: true,
									position: 'bottom-end',
									showConfirmButton: false,
									timer: 3000,
									type: 'success',
									title: 'Travel Date Deleted successfully'
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
            deleteTravelDatePrice(id){
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
                            axios.delete('/admin/travelpackages/traveldates/price/delete/'+id).then(response => {
								
                                self.travelPackageDetail();

								this.$swal({
									toast: true,
									position: 'bottom-end',
									showConfirmButton: false,
									timer: 3000,
									type: 'success',
									title: 'Price Deleted successfully'
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
            deleteItinerary(id){
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
                            axios.delete('/admin/travelpackages/itinerary/delete/'+id).then(response => {
								
                                self.travelPackageDetail();

								this.$swal({
									toast: true,
									position: 'bottom-end',
									showConfirmButton: false,
									timer: 3000,
									type: 'success',
									title: 'Itinerary Deleted successfully'
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
            updateImageName(id, name){
                const self = this;

               
        
                axios.post('/admin/travelpackages/image/name/update',{

                    id: id,
                    name: name

                    
                })
                .then(response => {
                    
                    var data = response.data;

                    //self.travel_package_id = data.obj.id;

                    self.travelPackageDetail();
                    this.$swal({
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 3000,
                        type: 'success',
                        title: 'Updated Image Name Successfully',
                        onOpen: function() {
                            var zippi = new Audio('https://res.cloudinary.com/dhmwdhirs/video/upload/v1558165617/audio/admin-sounds/bigbox.mp3')
                            zippi.play();
                        }
                    });
                    //self.successSound.play();
                    //self.getGalleryFiles();
                })
                .catch(error => {
                    Swal.showValidationMessage(
                    `Request failed: ${error}`
                    )
                })

            },  
            addIncludeTag (newTag) {
                const tag = {
                    name: newTag,
                    id: 0
                }
                this.travelPackageIncludeOptions.push(tag)
                this.travelPackage.includes.push(tag);
            },
            addExcludeTag (newTag) {
                const tag = {
                    name: newTag,
                    id: 0
                }
                this.travelPackageExcludeOptions.push(tag)
                this.travelPackage.excludes.push(tag);
            }
            
        }

    }

</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style src="@voerro/vue-tagsinput/dist/style.css"></style>

