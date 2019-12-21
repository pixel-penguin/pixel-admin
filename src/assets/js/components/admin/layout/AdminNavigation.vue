<template>
    <div class="sidebar" id="sidebar">
		<div class="sidebar-inner slimscroll">
            <div id="sidebar-menu" class="sidebar-menu">
                <ul>

                    <li class="menu-title">Main</li>

                    <li v-for="(navigation, index) in navigations" :key="index" v-bind:class="{'submenu': navigation.has_children}">
                        
                        
                        <a v-if="navigation.has_children == true" href="#">
                            <i :class="'fa '+ navigation.icon_name" aria-hidden="true"></i>
                            <span> {{navigation.name}}</span>
                            <span class="menu-arrow"></span>
                        </a>

                        <a v-if="navigation.has_children == false" :href="'/'+navigation.url">
                            <i :class="'fa '+ navigation.icon_name" aria-hidden="true"></i>
                            <span> {{navigation.name}}</span>
                        </a>

                        <ul  v-if="navigation.has_children == true" class="list-unstyled" style="display: none;">
                            <li v-for="(navigationSub, index2) in navigation.children" :key="index2">
                                <a :href="'/'+navigationSub.url"><i :class="'fa fa-lg fa-fw fa-'+ navigationSub.icon_name"></i> <span class="menu-item-parent">{{navigationSub.name}}</span></a>
                            </li>
                        </ul>
                    </li>';	
                    
                </ul>
                    
            </div>
		</div>
    </div>
</template>

<script>
    export default {
        name: 'tree-menu',
        data(){
            return{
                navigations:null,
            }
        },
        mounted() {
            const self = this;

            self.getNavigation();

            //alert('what');

            console.log('Component mounted.');

        },
        methods:{
            getNavigation(){
                const self = this;

                axios.get('/admin/json/navigation')
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    console.log(data);

                    self.navigations = data.obj;
                    //this.info = response.data.bpi

                    self.instNavigationAnimation();
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            instNavigationAnimation(){
                $(document).on('click', '#sidebar-menu a', {}, function(e) {
                //$("#sidebar-menu a").on('click', function(e) {
                    
                    //alert('yay');
                    
                    if ($(this).parent().hasClass("submenu")) {
                        e.preventDefault();
                    }
                    if (!$(this).hasClass("subdrop")) {
                        $("ul", $(this).parents("ul:first")).slideUp(350);
                        $("a", $(this).parents("ul:first")).removeClass("subdrop");
                        $(this).next("ul").slideDown(350);
                        $(this).addClass("subdrop");
                    } else if ($(this).hasClass("subdrop")) {
                        $(this).removeClass("subdrop");
                        $(this).next("ul").slideUp(350);
                    }
                });
                $("#sidebar-menu ul li.submenu a.active").parents("li:last").children("a:first").addClass("active").trigger("click");      
            }
        }

    }

</script>
