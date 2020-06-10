<template>
    <div>
		<div class="row">
			<div class="col-xs-4">
				<h4 class="page-title">Orders</h4>
			</div>
			
            <!--
            <div class="col-xs-8 text-right m-b-30">
				<a href="/admin/orders/create" class="btn btn-primary rounded"><i class="fa fa-plus"></i> Add Order</a>
			</div>
            -->
		</div>
		
		<div>
            <div class="ui container">
                <section>
                    <!--<input style="border:1px solid #CCC; padding:3px 10px; border-radius:10px; margin-bottom:10px" type="text" v-model="searchKey" placeholder="Filter Grab Voucher Here" />-->
                    <v-client-table :columns="columns" v-model="orders" :options="options">
                        
                        <!--<a slot="name" slot-scope="props" target="_blank" :href="props.row.name" class="glyphicon glyphicon-eye-open"></a>-->

                        <div slot="name" slot-scope="props">
                            <p>{{props.row.reference_code}}</p>
                        </div>

                        <div slot="products" slot-scope="props">
                            <div>
                                <a target="_blank" :href="'/invoice/'+props.row.reference_code"><div class="btn btn-primary">View Invoice</div></a>
                            </div>
                        </div>

                        <div slot="payment_status" slot-scope="props">                            
                            <select @change="changePaymentStatus(props.row.id, props.row.payment_status)" class="form-control" v-model="props.row.payment_status">
                                <option value="Pending">Pending</option>
                                <option value="Payment Received">Payment Received</option>
                            </select>                           
                        </div>

                        <div slot="delivery_status" slot-scope="props">                            
                            <select @change="changeDeliveryStatus(props.row.id, props.row.delivery_status)" class="form-control" v-model="props.row.delivery_status">
                                <option value="Pending">Pending</option>
                                <option value="Ready to ship">Ready to ship</option>
                                <option value="Package Picked Up">Package Picked Up</option>
                            </select>                           
                        </div>

                        <div slot="done" slot-scope="props">                            
                            <div @click="markAsDone(props.row.id)" class="btn btn-success">Mark as done</div>                     
                        </div>

                        

                    </v-client-table>
                </section>
            </div>
        </div>
        
        

    </div>
</template>

<script>
    export default {
        name: 'Orders',
        data(){
            return{
                cloudinaryCloudName: null,

                //table
                columns:['reference_code', 'products' , 'payment_status', 'delivery_status', 'done'],
                options: {
                    headings: {
                        name: 'Reference Code',
                        products: 'Products Ordered',
                        payment_status: 'Payment Status',
                        delivery_status: 'Delivery Status',
                        done: 'Done'
                    },
                    //editableColumns:['name'],
                    sortable: ['name'],
                    filterable: ['name']
                },
                orders:[],	
                //end table

                linkCopied:null,
	
				orders:[],					
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

            self.getOutstandingOrders();

           
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
            
            getOutstandingOrders(){
                const self = this;

                axios.get('/admin/orders/get/outstanding')
                .then(response => {
                    
                    var data = response.data;
                    
                    console.log(data);

                    self.orders = data.data;
                    
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
				self.orders = hash;
				//return hash;
			},
            markAsDone(orderId){
				const self = this;

				//console.log(rowId);
				
				this.$swal({
                    title: 'Are you sure?',
                    text: "You are changing the status of this order",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes please update!'
                    }).then((result) => {
                        if (result.value) {
                            axios.post('/admin/orders/changestatus',{
                                id:orderId
                            }).then(response => {
								self.getOutstandingOrders();

								this.$swal({
									toast: true,
									position: 'bottom-end',
									showConfirmButton: false,
									timer: 3000,
									type: 'success',
									title: 'Order Deleted successfully'
								})

                                self.getOutstandingOrders();
								//self.showAddForm = false;
							})
							.catch(e => {
								this.errors.push(e)
							})
                        }
                    })
			},            
            changeDeliveryStatus(id, value){
                const self = this;

                //console.log(id);

                axios.post('/admin/orders/updatedelivery',{
                    id: id,
                    value:value,
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

                    self.getOutstandingOrders();
                })
                .catch(error => {
                    self.$swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                    self.getOutstandingOrders();
                })
            },
            changePaymentStatus(id, value){
                const self = this;

                //console.log(id);

                axios.post('/admin/orders/updatepayment',{
                    id: id,
                    value:value,
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

                    self.getOutstandingOrders();
                })
                .catch(error => {
                    self.$swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                    self.getOutstandingOrders();
                })
            }
            
        }

    }

</script>
