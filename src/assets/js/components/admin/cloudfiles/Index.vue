<template>
    <div>
		<div class="row">
			<div class="col-md-12">
				<h4 class="page-title">Cloud Files</h4>
			</div>
            
            
			<div class="col-md-12 text-right m-b-30">
				<a  @click="uploadFileModal = true" href="#" class="btn btn-primary rounded"><i class="fa fa-plus"></i> Upload</a>
			</div>
            
		</div>
		
		<div>
            <div class="ui container">
                <section>
                    <div>
                        <h1>My Cloud Files</h1>
                        <div class="input-wrap">
                            <input v-model="searchKey" style="border:1px solid #CCC" id="searchbar" type="search" placeholder="Search a file..." />
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </div>
                        <div class="left">
                            
                            
                            <div  v-tooltip="cloudFile.name+'.'+cloudFile.file_extension" v-for="cloudFile in cloudFiles" :key="cloudFile.id" class="top-droppable folder tooltiper tooltiper-up" data-tooltip="0 file" id="folder1">
                                <!--<i class="fa fa-file-pdf-o fileIcon" aria-hidden="true"></i>-->
                                <i class="fa fa-file-o fileIcon" aria-hidden="true"></i>
                                <i class="fa fa-check" aria-hidden="true"></i>
                                <p style="overflow:hidden">{{cloudFile.name}}.{{cloudFile.file_extension}}</p>
                                
                                <div v-if="cloudFile.file_extension == 'pdf'" class="btn btn-default" v-clipboard:copy="'https://res.cloudinary.com/pixel-penguin/image/upload/v1/'+cloudFile.file_name+'.pdf'">
                                    <i style="font-size:12px; color:#333" class="fa fa-link" aria-hidden="true"></i> Copy url
                                </div>
                                <div v-else class="btn btn-default" v-clipboard:copy="appUrl+'/cloudfiles/download/'+cloudFile.id">
                                    <i style="font-size:12px; color:#333" class="fa fa-link" aria-hidden="true"></i> Copy url
                                </div>
                                
                                <i @click="destroyFile(cloudFile.id)" style="font-size:12px; color:#333; position: absolute; right:5px; top: 5px; color:#FFF; background:#f62d51; padding:5px; border-radius:50%; cursor:pointer" class="fa fa-remove" aria-hidden="true"></i>
                                
                            </div>
                            
                            
                            <!--
                            <div class="top-droppable folder tooltiper tooltiper-up" data-tooltip="0 file" id="folder2"><i class="fa fa-folder" aria-hidden="true"></i><i class="fa fa-check" aria-hidden="true"></i><p>Folder 2</p></div>
                            <div class="top-droppable folder tooltiper tooltiper-up" data-tooltip="0 file" id="folder3"><i class="fa fa-folder" aria-hidden="true"></i><i class="fa fa-check" aria-hidden="true"></i><p>Folder 3</p></div>
                            <div class="top-droppable folder tooltiper tooltiper-up" data-tooltip="0 file" id="folder4"><i class="fa fa-folder" aria-hidden="true"></i><i class="fa fa-check" aria-hidden="true"></i><p>Folder 4</p></div>
                            <div class="top-droppable folder tooltiper tooltiper-up" data-tooltip="0 file" id="folder5"><i class="fa fa-folder" aria-hidden="true"></i><i class="fa fa-check" aria-hidden="true"></i><p>Folder 5</p></div>
                            -->
                        </div>
                    </div>
                </section>
            </div>
        </div>
        
        
        <simple-modal v-model="uploadFileModal" title="Edit">
            <template slot="body">
                
                <label for="uploadFile"></label>
                <input v-on:change="onGalleryChange" id="uploadFile" type="file" />

                <div style="margin-top:10px; text-align:right">
                    <div @click="uploadFileModal = false" class="btn btn-primary">Cancel</div>
                    
                    <div v-if="fileUploading == false" @click="uploadFile" class="btn btn-primary">Upload</div>
                    <div v-if="fileUploading == true" class="btn btn-primary"><i class='fa fa-circle-o-notch fa-spin'></i> Uploading</div>    
                </div>
            </template>
            <template slot="footer">
                <button>OK</button>
            </template>
        </simple-modal>
    </div>
</template>

<script>

    import SimpleModal from 'simple-modal-vue';
    
    export default {
        name: 'Teams',
        data(){
            return{
                appUrl: null,
                cloudinaryCloudName: null,
                uploadFileModal: false,

                fileUploading: false, 
                galleryFiles: null,

                linkCopied:null,

				cloudFilesOriginal:[],		
				cloudFiles:[],					
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

            self.getAllFiles();

           
            self.cloudinaryCloudName = process.env.MIX_CLOUDINARY_CLOUD_NAME;
            self.appUrl = process.env.MIX_APP_URL;
            

            console.log('now');
            console.log(self.cloudinaryCloudName);
        },
        components: {
            SimpleModal         
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
            
            getAllFiles(){
                const self = this;

                axios.get('/json/cloudfiles')
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    console.log(data);

                    self.cloudFiles = data.obj;
                    self.cloudFilesOriginal = data.obj;
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

				for(i =  0, j = self.cloudFilesOriginal.length; i<j; i++){
					item = self.cloudFilesOriginal[i];
					if(typeof item['name'].toLowerCase() !== "undefined" && item['name'].toLowerCase().includes(self.searchKey. toLowerCase())){
						hash.push(item);
					}
				}

				//console.log(hash);
				self.cloudFiles = hash;
				//return hash;
			},
            destroyFile(cloudFileId){
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
                            axios.delete('/admin/cloudfiles/'+cloudFileId).then(response => {
								self.getAllFiles();

								this.$swal({
									toast: true,
									position: 'bottom-end',
									showConfirmButton: false,
									timer: 3000,
									type: 'success',
									title: 'Team Deleted successfully'
								})

                                self.getAllFiles();
								//self.showAddForm = false;
							})
							.catch(e => {
								this.errors.push(e)
							})
                        }
                    })
            },  
            
            onGalleryChange(e) {
                const self = this;
                
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                self.galleryFiles = files;
            },
            uploadFile(count = 0){
                const self = this;
                
                self.fileUploading = true;


                var formData = new FormData();
                formData.append('cloud_file_id', self.blog_id);
                formData.append('file_name', self.galleryFiles[0]); 

                axios.post('/admin/cloudfiles/file/upload', formData,formData,
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                
                })
                .then(response => {
                    //count++;
                    //self.uploadGallery(count);
                    self.fileUploading = false;
                    self.getAllFiles();
                    self.uploadFileModal = false;

                })
                .catch(error => {
                    self.$swal.showValidationMessage(
                        `Request failed: ${error}`
                    )

                    alert('Something went wrong with uploads');
                    
                    self.fileUploading = false;
                })
            
                //console.log(self.galleryFiles);
            },
            activate(id){
                const self = this;

                //console.log(id);

                axios.post('/admin/teams/activate',{
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

                    self.getAllFiles();
                })
                .catch(error => {
                    self.$swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                    self.getAllFiles();
                })
            }
            
        }

    }

</script>
<style scoped>

@import url(https://fonts.googleapis.com/css?family=Montserrat:300,400,700);header,nav ul,nav ul li{width:100%}
.input-wrap i,header .logo{transform:translateY(-50%)}
#searchbar,body,h1,h2,h3{font-family:Montserrat,sans-serif}

#searchbar,.right{border-radius:8px}
body{background:url(http://www.thomaspodgro.com/pen/hero-bg.jpg) top left no-repeat;background-size:cover;font-weight:400;min-height:100vh}
#wrapper{padding:100px 100px 50px 50px}
h1,h2,h3{font-weight:300}
header .logo,header ul li{display:inline-block;font-weight:700}
header{position:fixed;top:0;left:0;background:#fff;height:100px;line-height:100px;padding:0 100px;z-index:999}
header .logo{float:left;font-size:20px;color:#fff;background:#f95536;line-height:initial;position:relative;top:50%;padding:10px}
header ul{float:right;display:inline-block}
header ul li{float:left;text-transform:uppercase;font-size:14px;color:#333;margin-left:40px;letter-spacing:1px}
.folder p,h1{font-weight:300}
header ul li.active{color:#f95536}
nav{position:fixed;top:100px;left:0;height:calc(100vh - 100px);width:100px;z-index:3;background:rgba(0,0,0,.7)}
.input-wrap,.left,.right,nav ul{position:relative}
nav ul li{height:120px;border-bottom:1px solid rgba(255,255,255,.3);text-align:center;line-height:120px;cursor:pointer}
nav ul li.active{border-bottom:none;background:#f95536}
nav ul li.disabled:hover{background:rgba(0,0,0,.2);cursor:not-allowed}
nav ul li i.fileIcon{color:#fff;font-size:30px}
#searchbar,.input-wrap i{background:#fff;color:#333}
.main{width:60%;float:left;margin-left:100px;margin-top:50px}
.left{margin-top:30px}
.right{width:25%;float:right;background:#fff;padding:30px;height:70vh;min-height:500px;margin-top:50px;margin-bottom:10vh}
.left:after,.right:after{content:"";display:table;clear:both}
h1{font-size:50px;margin-bottom:30px}
.input-wrap i{position:absolute;right:40px;top:50%}
.folder,.item{margin-right:10px;position:relative}
#searchbar{display:block;width:100%;padding:20px 40px;font-size:18px}
#searchbar::-webkit-input-placeholder{color:#b9b9b9}
#searchbar::-moz-placeholder{color:#b9b9b9}
#searchbar:-ms-input-placeholder{color:#b9b9b9}
#searchbar:-moz-placeholder{color:#b9b9b9}
.item{cursor:-webkit-grab;margin-bottom:20px;padding:0 0 20px 20px;display:block;border-bottom:1px solid #ccc;background:rgba(255,255,255,.3);transition:.3s width ease-out,.3s height ease-out}
.item i{margin-right:10px}
.item i,.item p{display:inline-block;vertical-align:middle}
.item p{line-height:1.2;word-break:break-all;width:calc(100% - 50px)}
.is-dropped{transform:scale(0);transition:.2s all ease-out}
.folder{float:left;width:150px;height:200px;background:rgba(0,0,0,0);transition:.2s background-color ease-out;text-align:center}
.folder i.fileIcon{height:90px!important;width:100%!important;line-height:100px!important;margin:10px auto!important;font-size:90px!important;transition:.2s all ease-out}
.folder i.fa-check{color:#fff;background:rgba(0,0,0,.1);border-radius:50%;width:60px;height:60px;text-align:center;line-height:60px;position:absolute;left:0;right:0;top:34px;margin:auto;pointer-events:none;transform:scale(0);opacity:0;font-size:24px}
.folder.item-dropped i.fa-check{animation:animateValidation 1s linear}
@keyframes animateValidation{
0%{transform:scale(1.5);opacity:0}
40%,80%{transform:scale(1);opacity:1}
100%{transform:scale(0);opacity:0}
}
.folder p{cursor:default;opacity:.5;transition:.2s all ease-out}
.folder:hover .fileIcon{transform:scale(1.1)!important}
.folder:hover p{transform:translateY(6px);opacity:1}
.tooltiper .tooltip,.tooltiper-up .tooltip{font-size:.7rem;background:rgba(0,0,0,.7);padding:5px 10px;border-radius:5px;line-height:1.4em;opacity:0}
#folder1 i.fileIcon,#folder1-content h2 .fileIcon{color:#eb4141!important}
#folder2 i.fileIcon,#folder2-content h2 .fileIcon{color:#4bc97a}
#folder3 i.fileIcon,#folder3-content h2 .fileIcon{color:#6fdbeb}
#folder4 i.fileIcon,#folder4-content h2 .fileIcon{color:#eeca4e}
#folder5 i.fileIcon,#folder5-content h2 .fileIcon{color:#5b5b5b}
.tooltiper{position:relative;z-index:3}
.tooltiper .tooltip{min-width:110px;position:absolute;text-align:left;color:#fff;display:block;top:50%;left:120px;transform:translateY(-50%) scale(0);transform-origin:left;transition:transform .2s ease-out,opacity .1s ease-out .1s}
.tooltiper-up .tooltip{min-width:0;position:absolute;text-align:center;color:#fff;display:inline-block;top:-20px;left:50%;transform:translate(-50%,-50%) scale(0);transform-origin:bottom}
.tooltiper-up:hover .tooltip,.tooltiper:hover .tooltip{opacity:1;transition:transform .2s ease-out,opacity .1s ease-out}
.tooltiper:hover .tooltip{transform:translateY(-50%) scale(1)}
.tooltiper-up:hover .tooltip{transform:translate(-50%,-50%) scale(1)}
.tooltiper .tooltip:after{right:100%;top:50%;border:solid transparent;content:" ";height:0;width:0;position:absolute;pointer-events:none;border-right-color:rgba(0,0,0,.7);border-width:4px;margin-top:-4px}
.tooltiper-up .tooltip:after{border-width:7px 7px 0;border-color:rgba(0,0,0,.7) transparent transparent;right:0;left:0;margin:0 auto;top:100%}
.folder-content{display:none;position:absolute;height:420px;width:1012px;background:rgba(255,255,255,.9);z-index:10;box-shadow:0 10px 30px rgba(0,0,0,.1);left:150px;top:315px;border-radius:8px;padding:30px}
.folder-content h2{font-size:21px;padding-bottom:10px;margin-bottom:30px;border-bottom:1px solid #ccc;cursor:default}
.folder-content h2 i{margin-right:10px}
.easeout2{transition:.2s all ease-out}
.folder-content.closed{transform:scale(.8);opacity:0}
.close-folder-content{position:absolute;right:20px;top:20px;background:#f3f3f3;padding:5px 10px;border-radius:5px}
.close-folder-content:hover,.fileUpload span{background:#f95536}
.close-folder-content,.close-folder-content i{transition:.16s all ease-out}
.close-folder-content:hover i{color:#fff}
.folder-content .file{float:left;margin-right:20px;bottom:20px;text-align:center;padding:20px}
.folder-content p{font-size:14px}
.folder-content .file i{font-size:36px;margin-bottom:15px}
.ui-draggable-dragging{z-index:999}
.input-select-wrap{position:absolute;top:50%;left:0;right:0;transform:translateY(-50%);width:250px;height:70px;line-height:70px;margin:0 auto;text-align:center;transition:.3s all ease-out}
#fileSelect{color:transparent;position:relative;width:100%;height:100%;margin:0 auto;display:block;cursor:pointer}
.fileUpload p,.fileUpload span{display:inline-block;vertical-align:middle;transition:.3s all ease-out}
#fileSelect::-webkit-file-upload-button{visibility:hidden}
.fileUpload{position:absolute;top:0;left:0;width:100%;height:100%}
.fileUpload span{opacity:.8;height:40px;width:40px;line-height:40px;color:#fff;text-align:center;border-radius:50%;font-size:24px;margin-right:10px}
.fileUpload p{font-weight:400;line-height:30px;height:30px;font-size:18px}
.fileUpload p:after{content:"Add up to 5GB";display:block;height:0;font-weight:400;text-align:left;color:#999;line-height:10px;font-size:14px;margin-top:5px;opacity:0;transition:.3s all ease-out;transform:translateY(-18px)}
#draggableFile,#draggableFile>span{position:fixed;text-align:center;width:100%}
.input-select-wrap:hover .fileUpload p:after{opacity:1;height:10px;transform:translateY(0)}
.input-select-wrap:hover .fileUpload p{height:50px}
.input-select-wrap:hover .fileUpload span{opacity:1}
#draggableFile{color:#FFF;top:0;left:0;opacity:0;background:rgba(249,85,54,.7);height:0;transition:.6s opacity ease-out,0s height linear .6s;z-index:3}
.visible #draggableFile{opacity:1;height:100vh;transition:.6s opacity ease-out}
#draggableFile>span{display:block;font-size:30px;top:50%;height:0;transform:translateY(-50%);color:#fff;pointer-events:none}
#draggableFile>span>span{font-size:16px;opacity:.7;font-weight:300;color:#fff}
#myForm #result{background-color:#999;padding:10px;border-radius:10px;color:#222;margin-right:30px;margin-left:30px;border:2px solid #000;display:none}


</style>