<template>
    <div>

        <div v-if="loading" class="col-md-12" style="height:300px; background:url(https://res.cloudinary.com/dhmwdhirs/image/upload/v1563733239/loading/loading-1.gif) center no-repeat; ">

        </div>

        <div v-if="loading == false">
            <div class="col-md-12">
                <h2>Filter by dates</h2>
                <div class="form-group col-lg-3 col-sm-3">
                    <label class="text-white">Start Date</label><br>
                    <!--<input type="text" name="date1" class="date start form-control" placeholder="MM/DD/YYYY">-->
                    <datepicker :format="format" class="filterStatsProperty" v-model="start_date"></datepicker>
                </div>
                <div class="form-group col-lg-3 col-sm-3">
                    <label class="text-white">End Date</label><br>
                    <!--<input type="text" name="date2" class="date end form-control" placeholder="MM/DD/YYYY">-->
                    <datepicker :format="format" class="filterStatsProperty" v-model="end_date"></datepicker>
                </div>
            </div>
            <div class="col-md-12">
                <h2>Visitors and Page Views</h2>
                <apexchart type=line height=350 :options="chartOptions" :series="series" />
            </div>

            <div class="col-md-12">
                <h2>Browsers</h2>
                <apexchart type=pie width=600 :options="browserChart" :series="browserSeries" />
            </div>
            <div class="col-md-6">
                <h2>Top Referrers</h2>
                <table>
                    <tr>
                        <th>URL</th>
                        <th>Amount</th>
                    </tr>
                    <tr v-for="referrer in topReferrers" v-bind:key="referrer.url">
                        <td><a target="_blank" :href="'http://'+referrer.url">{{referrer.url}}</a></td>
                        <td style="text-align:right">{{referrer.pageViews}}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h2>Most Visited Pages</h2>
                <table>
                    <tr>
                        <th>URL</th>
                        <th>Amount</th>
                    </tr>
                    <tr v-for="visitedPage in topVisitedPages" v-bind:key="visitedPage.url">
                        <td><a target="_blank" :href="visitedPage.url">{{visitedPage.url}}</a></td>
                        <td style="text-align:right">{{visitedPage.pageViews}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>

<script>

    import Datepicker from 'vuejs-datepicker';

    export default {
        name: 'Testimonials',
        data(){
            return{
                loading:true,

                cloudinaryCloudName: null,

                topReferrers:[],
                topVisitedPages:[],

                format: 'yyyy-MM-dd',
                start_date: new Date((new Date()).getFullYear(), new Date().getMonth(), 1),
                end_date: new Date(),      
                series: [],
                chartOptions: {
                    chart: {
                        zoom: {
                            enabled: false
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: [5, 7, 5],
                        curve: 'straight',
                        dashArray: [0, 0, 0]
                    },

                    title: {
                        text: 'Analytics',
                        align: 'left'
                    },
                    markers: {
                        size: 0,
                        
                        hover: {
                            sizeOffset: 6
                        }
                    },
                    xaxis: {
                        type: 'datetime',
                    
                    },
                    tooltip: {
                        y: [{
                            title: {
                            formatter: function (val) {
                                return val + " "
                            }
                            }
                        }, {
                            title: {
                            formatter: function (val) {
                                return val + ""
                            }
                            }
                        }, {
                            title: {
                            formatter: function (val) {
                                return val;
                            }
                            }
                        }]
                    },
                    grid: {
                        borderColor: '#f1f1f1',
                    }
                },

                browserSeries: [100],
                browserChart: {
                    labels: ['Loading'],
                    responsive: [{
                        breakpoint: 480,
                        options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                        }
                    }]
                }
                
            }
        },
        components: {
            Datepicker,        
        },
        mounted() {
            const self = this;

            self.cloudinaryCloudName = process.env.MIX_CLOUDINARY_CLOUD_NAME;

            //alert('hi');

            self.getAnalytics();
            
        },
        watch: {
            start_date: function (val) {
                const self = this;
                //this.fullName = val + ' ' + this.lastName
                self.getAnalytics();
            },
            end_date: function (val) {
                const self = this;
                //this.fullName = this.firstName + ' ' + val
                self.getAnalytics();
            }
        },
        methods:{
            
            getAnalytics(){
                const self = this;

                self.loading = true;
                //console.log(self.start_date);

                axios.get('/admin/dashboard/json/get-analytics/'+self.start_date+'/'+self.end_date)
                .then(response => {
                    //console.log(response);

                    var data = response.data;

                    //self.pageViews = data.pageViews;
                    //self.visitors = data.visitors;
                    self.series = [                        
                        {
                            name: "Page Views",
                            data: data.pageViews
                        },
                        {
                            name: 'Visitors',
                            data: data.visitors
                        }
                    ];

                    self.browserChart = {
                        labels: data.topBrowsers.names,
                        responsive: [{
                            breakpoint: 480,
                            options: {
                            chart: {
                                width: 200
                            },
                            legend: {
                                position: 'bottom'
                            }
                            }
                        }]
                    }


                    self.browserSeries = data.topBrowsers.sessions;

                    self.topReferrers = data.top_referrers;
                    self.topVisitedPages = data.top_visited_pages;

                    self.loading = false;
                    
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                    self.loading = false;
                })
            },
            
            
        }

    }

</script>
