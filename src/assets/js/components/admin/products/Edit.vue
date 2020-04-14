<template>
    <div>
		<div class="row">

            <div class="col-md-12" style="margin-bottom:20px">
                <ul class="nav nav-tabs">
                    <li @click="step=1" v-bind:class="{ active: step == 1 }"><a href="#">Basic Detail</a></li>
                    <li v-if="product.id != 0" @click="step=2" v-bind:class="{ active: step == 2 }"><a href="#">Item Types</a></li>
                    <li v-if="product.id != 0" @click="step=3" v-bind:class="{ active: step == 3 }"><a href="#">Detail</a></li>
                    <li v-if="product.id != 0" @click="step=4" v-bind:class="{ active: step == 4 }"><a href="#">Pricing</a></li>
                    <li v-if="product.id != 0" @click="step=5" v-bind:class="{ active: step == 5 }"><a href="#">Images</a></li>
                    <li v-if="product.id != 0" @click="step=6" v-bind:class="{ active: step == 6 }"><a href="#">Extra</a></li>
                </ul>
            </div>

            <div v-if="step == 1">
                <form @submit="updateProduct">
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Name</label>
                            <input required class="form-control" type="text" v-model="product.name">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Brand</label>
                            <input required class="form-control" type="text" v-model="product.brand">
                        </div>
                    </div>
                                        
                    <div class="col-md-12">

                        <div class="form-group">
                            <label>Categories</label>

                            <treeselect
                                instanceId="locations"
                                :multiple="false"
                                :options="productCategoryOptions"
                                placeholder="Select your location(s)..."
                                v-model="product.product_category_id"
                                :disable-branch-nodes="true" 
                            />

                        </div>
                    </div>
                    <div class="col-md-12">

                        <div class="form-group">
                            <label>Tags</label>

                            <tags-input element-id="tags"
                            v-model="product.tags"></tags-input>
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
                <form @submit="updateProduct">
                    
                    
                    <div class="col-md-12">
                        
                        <div class="row">
                            <div class="col-sm-2 col-md-2" v-for="color in product.colors" :key="color.id" style="text-align:center;margin-bottom:20px">
                                <label>{{color.name}}</label>
                                <i :style="'background:'+color.code+'; height:50px; width:50px; border-radius: 50%; display:block; margin:auto'"></i>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-12">

                        <div class="form-group">
                            <label>Colours</label>

                            <multiselect
                                v-model="product.colors"
                                :options="colors.color_options"
                                label="name" 
                                track-by="name"
                                multiple >
                            </multiselect>
                        </div>
                    </div>

                    <div class="col-md-12">

                        <div class="form-group">
                            <label>Attributes</label>

                            <multiselect
                                v-model="product.attributes"
                                :options="attributes.attribute_options"
                                label="name" 
                                track-by="name"
                                multiple >
                            </multiselect>
                        </div>
                    </div>

                    <div class="col-md-12">

                        <div v-for="product in product.attributes" :key="product.id" class="form-group">
                            <label>{{product.name}}</label>

                            <tags-input element-id="value"
                            v-model="product.value"></tags-input>

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
                <form @submit="updateProduct">
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Summary</label>
                            <textarea required class="form-control" v-model="product.detail_summary"></textarea>
                        </div>
                    </div>

                    <div class="col-md-12">

                        <div class="form-group">
                            <label>Content</label>

                            <div style="height:auto" @click="openEditor" class="form-control" v-html="product.detail" ></div>
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
                <form @submit="updateProductPrice">
                    
                    
                    <div class="col-md-12">

                        <div class="row" style="margin-bottom:30px">
                            <div class="col-md-3">
                                <label>Product Name</label>
                            </div>
                            <div class="col-md-3">
                                <label>Price</label>
                            </div>
                            <div class="col-md-3">
                                <label>Discounted Price</label>
                            </div>
                            <div class="col-md-3">
                                <label>Has Discount</label>
                            </div>
                        </div>
                        
                        <div v-for="price in product.prices" :key="price.id" class="form-group">                            
                            <div class="row">
                                <div class="col-md-3">
                                    <label>{{price.name}}</label>
                                </div>
                                <div class="col-md-3">
                                    <input required class="form-control" type="number" v-model="price.price">
                                </div>
                                <div class="col-md-3">
                                    <input v-if="price.is_discount" required class="form-control" type="number" v-model="price.discounted_price">
                                </div>
                                <div class="col-md-3">
                                    <p-check class="p-switch" name="check" color="success" v-model="price.is_discount"></p-check>
                                </div>
                        
                        
                                
                            </div>
                            
                        </div>
                    </div>

                    <div class="col-md-12">
                        

                        <div class="m-t-20 text-center">
                            <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                        </div>
                        
                    </div>
                    
                </form>
            </div>

            <div v-if="step == 5">
                <div class="col-md-12">
                    
                    <div class="form-group" v-if="product.id != 0">
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
                                <li class="col-md-4 col-sm-2 col-xs-4 col-lg-2 list-group-item" v-for="item in productGallery" :key="item.id" >
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
                <form @submit="updateProduct">
                    
                    
                     <div class="col-md-12">

                        <div class="form-group">
                            <label>Featured</label>

                            <p>
                                <p-check class="p-switch" name="check" color="success" v-model="product.featured"></p-check>
                            </p>

                        </div>
                    </div>

                    <div class="col-md-12">

                        <div class="form-group">
                            <label>Published/Active</label>

                            <p>
                                <p-check class="p-switch" name="check" color="success" v-model="product.active"></p-check>
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
                v-model="product.detail"
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

    </div>
</template>

<script>
    
    import Multiselect from 'vue-multiselect'
    import Editor from '@tinymce/tinymce-vue';
    import SimpleModal from 'simple-modal-vue'
    import Datepicker from 'vuejs-datepicker';
    import Treeselect from '@riophae/vue-treeselect'
    import '@riophae/vue-treeselect/dist/vue-treeselect.css'

    export default {
        name: 'Edit-Product',
        data(){
            return{
                cloudinaryCloudName:null,

                step: 1,

                loading: false,
                galleryLoading: false,
                previewImageUrl:null,
                product_id: 0,
                productGallery: [],
                product:{

                    //step 1
                    id:0,
                    name: null,
                    brand: null,
                    product_category_id:null,
                    tags: [],

                    //step3
                    detail_summary: '',
                    detail: 'Please add product detail',

                    //step 6
                    featured: false,
                    active: false,
                },

                colors:{
                    color_options:[],
                    product_colors:[]
                },

                attributes:{
                    attribute_options:[],
                },

                successSound:new Audio('https://res.cloudinary.com/dhmwdhirs/video/upload/v1558165617/audio/admin-sounds/bigbox.mp3'),

                productCategoryOptions:[],
                productCategories:[],
                tags: [],

                screenHeight: null,
                editContent: false,
            }
        },
        props: ['product_id_link'],
        components: { 
            Multiselect,
            'editor': Editor ,
            SimpleModal, 
            Datepicker,
            Treeselect
        },
        mounted() {
            const self = this;

            self.cloudinaryCloudName = process.env.MIX_CLOUDINARY_CLOUD_NAME;

            self.product_id = self.product_id_link;
            
            if(self.product_id > 0){
                self.productDetail();
            }

            
            self.getGalleryFiles();
            self.getProductCategories();
            self.getProductColors();
            self.getProductAttributes();

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

            getProductAttributes(){
                const self = this;

                axios.get('/json/productattributes/get')
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.attributes.attribute_options = data.obj;

                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            getProductColors(){
                const self = this;

                axios.get('/json/productcolors/get')
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.colors.color_options = data.obj;

                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            getProductCategories(){
                const self = this;

                axios.get('/json/productcategories/get')
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.productCategoryOptions = data.obj;

                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            onOrder: function (event) {
                const self = this;

                console.log('Ordering');
                this.productGallery.splice(event.newIndex, 0, this.productGallery.splice(event.oldIndex, 1)[0])

                self.saveGalleryOrder();
            },
            productDetail(){
                const self = this;

                axios.get('/json/product/detail/'+self.product_id)
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.product = data.obj;

                    self.product.date_created = new Date(self.product.date_created);

                    self.product_id = self.product.id;

                    self.productCategories = data.obj.categories;

                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            updateProductPrice(e){

                const self = this;

                e.preventDefault();
        
                axios.post('/admin/products/price/update',{
                    //step 1
                    prices: self.product.prices,
                    

                })
                .then(response => {
                    
                    var data = response.data;

                    self.productDetail();
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
                    self.productDetail();
                    self.getGalleryFiles();
                })
                .catch(error => {
                    Swal.showValidationMessage(
                    `Request failed: ${error}`
                    )
                })

            },
            updateProduct(e){
                const self = this;

                e.preventDefault();
        
                axios.patch('/admin/products/'+self.product_id,{
                    //step 1
                    name: self.product.name,
                    brand: self.product.brand,
                    product_category_id: self.product.product_category_id,
                    tags: self.product.tags,

                    //step 2
                    colors: self.product.colors,
                    attributes:self.product.attributes,
                    //step 3
                    detail: self.product.detail,

                    //step 6
                    featured: self.product.featured,
                    active: self.product.active,
                    detail_summary: self.product.detail_summary

                })
                .then(response => {
                    
                    var data = response.data;

                    self.product_id = data.obj.id;

                    
                    self.productDetail();
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

                    if(data.redirect == true){
                        window.location = '/admin/products/'+data.obj.id+'/edit';
                    }

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

                console.log(self.productGallery);

                axios.post('/admin/products/gallery/order/'+self.product_id,{
                    productGallery:self.productGallery,
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
                    formData.append('product_id', self.product_id);
                    formData.append('image_name', self.galleryFiles[count]); 

                    axios.post('/admin/products/gallery/upload', formData,formData,
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

                axios.get('/json/product/gallery/'+self.product_id)
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.productGallery = data.obj;
                    
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
                            axios.delete('/admin/products/gallery/delete/'+galleryId).then(response => {
								
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

