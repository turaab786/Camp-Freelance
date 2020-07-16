<template>
        <section class="users-list-wrapper">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Filters</h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="feather icon-chevron-down"></i></a></li>
                            <li><a data-action=""><i class="feather icon-rotate-cw users-data-filter"></i></a></li>
                            <li><a data-action="close"><i class="feather icon-x"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <div class="users-list-filter">
                            <div class="row">
                                <div class="col-sm-4 col-lg-4">
                                    <label for="users-list-role">Category Name</label>
                                        <input type="text" placeholder="Category Name" class="form-control" v-model="filter.name">
                                </div>
                                <div class="col-sm-4 col-lg-4">
                                    <label for="users-list-status">Parent</label>
                                    <Select2 v-model="filter.parent" :options="parents" />
                                </div>
                                <div class="col-sm-4 col-lg-2">
                                    <br>
                                    <button @click="populateTable()" type="button" class="btn btn-primary">Filter</button>
                                </div>
                                <div class="col-sm-4 col-lg-2">
                                    <br>
                                    <button @click="clearFilter()" type="button" class="btn btn-default">Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="basic-examples">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                            <div class="table-responsive">
                              <table class="table zero-configuration">
                                  <thead>
                                      <tr>
                                          <th>Name</th>
                                          <th>Parent</th>
                                          <th>Action</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr v-for="(category, key) in categories" :key="key">
                                          <td><i class="fa" :class="category.icon"></i>  {{category.name}}</td>
                                          <td v-if="category.parent !== null">{{category.parent.name}}</td>
                                          <td v-if="category.parent === null">Default</td>
                                          <td>
                                            <button @click="getCategory(category.id);" type="button" class="btn btn-icon btn-flat-info mr-1 mb-1 waves-effect waves-light" data-toggle="modal" data-target="#categoryEditFormModal">
                                                <i class="feather icon-edit-2"></i>
                                            </button>
                                            <button @click="deleteCategory(category.id)" type="button" class="btn btn-icon btn-flat-danger mr-1 mb-1 waves-effect waves-light">
                                                <i class="feather icon-delete"></i>
                                            </button>
                                          </td>
                                      </tr>
                                      <tr v-if="categories.length == 0">
                                          <td class="text-center" colspan="3">No Data!</td>
                                      </tr>
                                  </tbody>
                                  <tfoot>
                                      <tr>
                                          <th>Name</th>
                                          <th>Parent</th>
                                          <th>Action</th>
                                      </tr>
                                  </tfoot>
                              </table>
                          </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade text-left" id="categoryEditFormModal" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel33" aria-hidden="true">
                 <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                     <div class="modal-content">
                         <div class="modal-header">
                             <h4 class="modal-title" id="myModalLabel33">Category Form </h4>
                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 <span aria-hidden="true">&times;</span>
                             </button>
                         </div>
                         <form action="#" @submit.prevent="editCategory">
                             <div class="modal-body">
                                 <label>Name: </label>
                                 <div class="form-group">
                                     <input type="text" placeholder="Category Name" class="form-control" :class="{ 'is-invalid': submitted && $v.category.name.$error }" v-model="category.name">
                                    <div v-if="submitted && !$v.category.name.required" class="invalid-feedback">Name is required</div>
                                    <div v-if="submitted && !$v.category.name.alpha" class="invalid-feedback">Name should be only aplhabets</div>
                                 </div>
                                 <label>Slug: </label>
                                 <div class="form-group">
                                     <input type="text" placeholder="Category Slug" class="form-control" :class="{ 'is-invalid': submitted && $v.category.slug.$error }" v-model="category.slug">
                                    <div v-if="submitted && !$v.category.slug.required" class="invalid-feedback">Slug is required</div>
                                 </div>
                                 <label>Icons: </label>
                                 <div class="form-group">
                                     <Select2 v-model="category.icon" :options="icons" :settings="selectSettings" :class="{ 'is-invalid': submitted && $v.category.icon.$error }" />
                                     <div v-if="submitted && !$v.category.icon.required" class="invalid-feedback">Icon is required</div>
                                 </div>
                                 <label>Parent: </label>
                                 <div class="form-group">
                                     <Select2 v-model="category.parent" :options="parents" :settings="selectSettings" :class="{ 'is-invalid': submitted && $v.category.parent.$error }" />
                                     <div v-if="submitted && !$v.category.parent.required" class="invalid-feedback">Parent is required</div>
                                 </div>
                             </div>

                             <div class="modal-footer">
                                <div v-if="spinner" class="spinner-border ml-auto" role="status" aria-hidden="true"></div>
                                 <button type="submit" class="btn btn-primary">Save</button>
                             </div>
                         </form>
                     </div>
                 </div>
            </div>
        </section>
</template>

<script>
    import { required, alpha } from 'vuelidate/lib/validators';
    import Select2 from 'v-select2-component';
    export default {
        data() {
            return {
                category: {
                    name: '',
                    icon: 'fa-500px',
                    parent: 0,
                },
                submitted: false,
                parents: [{id: 0, text: "Default"}],
                icons: [],
                spinner: false,
                selectSettings: {
                    width: '100%',
                    dropdownParent: '#categoryEditFormModal',
                    escapeMarkup: function (markup) {
                        return markup;
                    }
                },
                categories: [],
                filter: {
                    name: '',
                    parent: '',
                },
                category: {
                    name: '',
                    icon: 'fa-500px',
                    parent: 0,
                    slug: ''
                },
                categoryId: 0
            }    
        },
        created() {
            this.populateTable();
            this.populateDropdowns();
        },
        validations: {
            category: {
                name: { required },
                icon: { required },
                parent: { required },
                slug: { required }
            }
        },
        methods: {
            clearFilter() {
                this.filter.name = '';
                this.filter.parent = '';
                this.populateTable();
            },
            populateDropdowns() {
                //populating icons dropdown
                axios.get('fonts/icons/icons.json').then( response => {
                    let icons = response.data;
                    for (var key in icons) {
                        this.icons.push({
                            id: icons[key],
                            text: '<i class="fa ' + icons[key] + '"></i> ' + icons[key]
                        });
                    }
                } ).catch((error) => {
                    console.log(error);
                });

                //populating parent dropdown
                axios.get('support/category/all').then( response => {
                    let categories = response.data.categories;
                    for (var key in categories) {
                        this.parents.push({
                            id: categories[key].id,
                            text: categories[key].name
                        });
                    }
                } ).catch((error) => {
                    console.log(error);
                });
            },
            populateTable() {
                //populating the table
                axios.get('support/category/all/list?name=' + this.filter.name + '&parent=' + this.filter.parent).then( response => {
                    this.categories = response.data.categories;

                } ).catch((error) => {
                    console.log(error);
                });
            },
            editCategory(){
                this.submitted = true;
                this.spinner = true;

                //stop here if form is invalid
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }
                axios.post('support/category/' + this.categoryId + '/update', this.category).then( response => {
                    this.spinner = false;
                    this.$toastr.success(response.data.message, 'Success', {timeOut: 5000, escapeHtml: false});
                    location.reload();                  
                } ).catch((error) => {
                    this.spinner = false;
                    let message = error.response.data.message;
                    message += '<ul>';
                    let errors = error.response.data.errors;

                    for (const [key, value] of Object.entries(errors)) {
                        if (value[0] !== '') {
                            message += '<li>' + value[0] + '<li>';
                        } 
                    }

                    message += '</ul>';
                    this.$toastr.error(message, 'Oops', {timeOut: 5000, escapeHtml: false});
                   
                });
            },

            getCategory(categoryId) {
                this.categoryId = categoryId;
                axios.get('support/category/'+ categoryId +'/get').then( response => {
                    this.category.name = response.data.category.name;
                    this.category.parent = response.data.category.parent_id;
                    this.category.slug = response.data.category.slug;
                    this.category.icon = response.data.category.icon;

                } ).catch((error) => {
                    console.log(error);
                });
            },
            deleteCategory(categoryId) {
                this.$swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover it back!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it'
                    }).then((result) => {
                        if (result.value) {

                            axios.get('support/category/'+ categoryId +'/delete').then( response => {
                                this.$swal(
                                    'Deleted!',
                                    'Category has been deleted.',
                                    'success'
                                );
                                this.populateTable();

                            } ).catch((error) => {
                                console.log(error);
                            });


                            
                        // For more information about handling dismissals please visit
                        // https://sweetalert2.github.io/#handling-dismissals
                        } else if (result.dismiss) {
                            this.$swal(
                                'Cancelled',
                                'Your imaginary file is safe :)',
                                'error'
                            )
                        }
                    })
            }
        }
    }
</script>