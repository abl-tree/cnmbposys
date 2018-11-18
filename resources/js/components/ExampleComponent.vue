<template>
    <div class="card">
        <div class="card-body">
            <div  class="row" style='margin:0px 0px 0px 0px;padding:0px 0px 0px 0px'>
                <div class="container p-0 mB-0" style="width:100%;margin-top:0px">
                    <div class="row mb-2">
                        <h3>Table</h3>
                    </div>
                    <div class="container mB-2">
                        <div class="row">
                        <div class="col">
                            <div class="layers bd bgc-white p-20">
                                <div class="layer w-100 mB-10">
                                    <h6 class="lh-1">Bounce Rate</h6>
                                </div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                    <div class="peer peer-greed">
                                        <span id="sparklinedash4"></span>
                                    </div>
                                    <div class="peer">
                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500">33%</span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="layers bd bgc-white p-20">
                                <div class="layer w-100 mB-10">
                                    <h6 class="lh-1">Bounce Rate</h6>
                                </div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                    <div class="peer peer-greed">
                                        <span id="sparklinedash4"></span>
                                    </div>
                                    <div class="peer">
                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500">33%</span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="layers bd bgc-white p-20">
                                <div class="layer w-100 mB-10">
                                    <h6 class="lh-1">Bounce Rate</h6>
                                </div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                    <div class="peer peer-greed">
                                        <span id="sparklinedash4"></span>
                                    </div>
                                    <div class="peer">
                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500">33%</span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="layers bd bgc-white p-20">
                                <div class="layer w-100 mB-10">
                                    <h6 class="lh-1">Bounce Rate</h6>
                                </div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                    <div class="peer peer-greed">
                                        <span id="sparklinedash4"></span>
                                    </div>
                                    <div class="peer">
                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500">33%</span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="layers bd bgc-white p-20">
                                <div class="layer w-100 mB-10">
                                    <h6 class="lh-1">Bounce Rate</h6>
                                </div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                    <div class="peer peer-greed">
                                        <span id="sparklinedash4"></span>
                                    </div>
                                    <div class="peer">
                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500">33%</span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- table manipulation elements -->
                    <div class="container p-2">
                        <div class="row p2">
                        <div class='col-md-3 p-0'>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-sm-4">
                                        <select v-model="selected" class='form-control p-0' name="" id="displayNo">
                                            <option value="/api/debtors/5" selected>5</option>
                                            <option value="/api/debtors/10">10</option>
                                            <option value="/api/debtors/15">15</option>
                                            <option value="/api/debtors/20">20</option>
                                        </select>
                                    </div>
                                    <div class="col pt-2">
                                        <label for="displayNo">No. of Record</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 p-0">
                            <nav aria-label="Page navigation">
                                <ul class="pagination">
                                    <li  class="page-item">
                                        <a class="page-link"  tabindex="-1">Previous</a>
                                    </li>
                                    <li class="page-item disabled">
                                        <a class="page-link text-dark" href="#"></a></li>
                                    <li  class="page-item">
                                        <a class="page-link"  href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class='col-md-4 p-0'>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-md-2 pt-2" style='c'>
                                        <span class='pull-right'>Search: </span>
                                    </div>
                                    <div class="col" style='padding-right:0px;'>
                                        <input type="text" class="form-control" style='right:0'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                        
                </div> 
                <div class='p-0' style="overflow-x:auto">
                    <table id="canteen-table-display p-0" class="table table-hover" style="font-size:.8em;">
                    <thead>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Department</th>
                        <th>Manage</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Department</th>
                        <th>Manage</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Department</th>
                        <th>Manage</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Department</th>
                        <th>Manage</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Department</th>
                        <th>Manage</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Department</th>
                        <th>Manage</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Department</th>
                        <th>Manage</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Department</th>
                        <th>Manage</th>
                    </thead>
                    <tbody>
                        <!-- <tr v-for='debtor in debtors' v-bind:key="debtor.id" v-bind:id="debtor.id">
                            <td>{{debtor.name}}</td>
                            <td>{{debtor.address}}</td>
                            <td v-bind:id="debtor.dept_id">{{(debtor.department.name)}}</td>
                            <td>
                                <div class="btn-group">
                                    <button  @click="edit = true" class="btn btn-warning"><span class="ti ti-pencil-alt"></span></button>
                                    <button @click="deleteDebtor(debtor.id)" class="btn btn-danger"><span class="ti ti-trash"></span></button>
                                </div>
                            </td>
                        </tr> -->
                        <tr>
                            <td>Jill</td>
                            <td>Smith</td>
                            <td><img src="/images/1.jpg" alt="" class="img-circle"></td>
                            <td><img src="/images/bg.jpg" alt="" class="img-responsive img-circle"></td>
                            <td>50</td>
                            <td>50</td>
                            <td>50</td>
                            <td>50</td>
                            <td>50</td>
                            <td>50</td>
                            <td>50</td>
                            <td>50</td>
                            <td>50</td>
                            <td>50</td>
                            <td>50</td>
                            <td>50</td>
                            <td>50</td>
                            <td>50</td>
                            <td>50</td>
                            <td>50</td>
                            <td>50</td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.card,
.card-header {
  background-color: white;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: rgba(221, 221, 221, 0.8);
}
::-webkit-scrollbar {
  height: 4px;
  padding: 0px;
}
::-webkit-scrollbar-thumb:hover {
  background: rgba(51, 51, 51, 0.4);
}
::-webkit-scrollbar-track {
  background: rgba(221, 221, 221, 0.4);
}
::-webkit-scrollbar-thumb:window-inactive {
  background: rgba(141, 141, 141, 0.4);
}
</style>

<script>
export default {
  mounted() {
    console.log(this.depts);
  }
  // props:['depts'], //data from blade
  // data(){
  //     return{

  //         p:'', //records to display
  //         selected:'/api/debtors/5',
  //         search: '',
  //         seen: true,
  //         arrows: 'ti ti-angle-up',
  //         debtors:[],
  //         debtor:{
  //             name:'',
  //             address:'',
  //             dept_id:1,
  //         },
  //         debtor_id:'',
  //         pagination:{},
  //         edit: false
  //     }
  // },

  // created(){
  //     this.fetchDebtors(this.selected);
  // },

  // watch:{
  //     search: function(val){
  //         this.fetchDebtors(this.selected+'/'+val);
  //     }
  // },

  // methods:{
  //     fetchDebtors: function(page_url){
  //         let vm = this;
  //         page_url = page_url || '/api/debtors'
  //         fetch(page_url)
  //         .then(res=>res.json())
  //         .then(res=>{
  //             this.debtors = res.data;
  //             console.log(res.data);
  //             vm.makePagination(res.meta,res.links);
  //         })
  //         .catch(err => console.log(err));
  //     },
  //     makePagination:function(meta,links){
  //         let pagination = {
  //             current_page: meta.current_page,
  //             last_page: meta.last_page,
  //             next_page: links.next,
  //             prev_page: links.prev
  //         }
  //         this.pagination = pagination
  //     },
  //     addDebtor: function(){
  //         if(this.edit===false){
  //             //add
  //             fetch('api/debtor',{
  //                 method: 'post',
  //                 body: JSON.stringify(this.debtor),
  //                 headers:{
  //                     'content-type' : 'application/json'
  //                 }
  //             })
  //             .then(res => res.json())
  //             .then(data => {
  //                 this.debtor.name ='';
  //                 this.debtor.address = '';
  //                 this.debtor.dept_id = 1;
  //                 this.fetchDebtors(this.selected);
  //             })
  //             .catch(err => console.log(err))
  //         }else{

  //         }
  //     },
  //     deleteDebtor: function(id){
  //         this.$swal({
  //             title: 'Are you sure?',
  //             text: "You won't be able to revert this!",
  //             type: 'warning',
  //             showCancelButton: true,
  //             confirmButtonColor: '#3085d6',
  //             cancelButtonColor: '#d33',
  //             confirmButtonText: 'Yes, delete it!'
  //             }).then((result) => {
  //                 if (result.value) {
  //                     fetch(`api/debtor/${id}`,{
  //                     method: 'delete'
  //                 })
  //                 .then(res => res.json())
  //                 .then(data => {
  //                     this.$swal(
  //                         'Deleted!',
  //                         'Your file has been deleted.',
  //                         'success'
  //                     );
  //                     this.fetchDebtors(this.selected);
  //                 })
  //                 .catch(err => console.log(err))
  //                 }
  //             })
  //     }

  // },
};
</script>
