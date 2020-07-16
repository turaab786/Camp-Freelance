<template>
    <!-- article list start -->
    <section class="users-list-wrapper">
        <!-- article filter start -->
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
                            <div class="col-sm-4 col-lg-3">
                                <label for="users-list-role">Article Title</label>
                                <input  type="text" placeholder="Article Title" class="form-control" v-model="filter.title">
                            </div>
                            <div class="col-sm-4 col-lg-3">
                                <label for="users-list-status">Category</label>
                                <Select2 v-model="filter.category" :options="categories" />
                            </div>
                            <div class="col-sm-4 col-lg-3">
                                <label for="users-list-status">Belongs To</label>
                                <select v-model="filter.belongsTo" class="form-control">
                                    <option value="seller">Seller</option>
                                    <option value="buyer">Buyer</option>
                                </select>
                            </div>
                            <div class="col-sm-4 col-lg-3">
                                <br>
                                <button @click="populateTable()" type="button" class="btn btn-primary">Filter</button>
                                <button @click="clearFilter()" type="button" class="btn btn-default">Clear</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- users filter end -->
        <!-- Ag Grid users list section start -->
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
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Belongs To</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                    <tr v-for="(article, key) in articles" :key="key">
                                        <td>{{article.title}}</td>
                                        <td>{{article.category.name}}</td>
                                        <td>{{ article.belongs_to }}</td>
                                        <td>{{ article.created_at | formatDate }}</td>
                                        <td>
                                            <button @click="redirectToEditAritclePage(article.id);" type="button" class="btn btn-icon btn-flat-info mr-1 mb-1 waves-effect waves-light" data-toggle="modal" data-target="#categoryEditFormModal">
                                                <i class="feather icon-edit-2"></i>
                                            </button>
                                            <button @click="deleteArticle(article.id)" type="button" class="btn btn-icon btn-flat-danger mr-1 mb-1 waves-effect waves-light">
                                                <i class="feather icon-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="articles.length == 0">
                                        <td></td>
                                        <td></td>
                                        <td class="text-center">No Data!</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                  </tbody>
                                  <tfoot>
                                      <tr>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Belongs To</th>
                                        <th>Created At</th>
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
        <!-- Ag Grid article list section end -->
    </section>
    <!-- article list ends -->
</template>
<script>
    import Select2 from 'v-select2-component';
    export default {
        data() {
            return {
                articles: [],
                categories: [],
                filter: {
                    title: '',
                    category: '',
                    belongsTo: ''
                }
            }    
        },
        created() {
            this.populateTable();
            this.populateCategoryDropdown();
        },
        methods: {
            populateCategoryDropdown() {
                //populating parent dropdown
                axios.get('support/category/all').then( response => {
                    let categories = response.data.categories;
                    for (var key in categories) {
                        this.categories.push({
                            id: categories[key].id,
                            text: categories[key].name
                        });
                    }
                } ).catch((error) => {
                    console.log(error);
                });
            },
            clearFilter() {
                this.filter.title = '';
                this.filter.category = '';
                this.filter.belongsTo = '';
                this.populateTable();
            },
            populateTable() {
                //populating the table
                axios.get('support/article/all?title=' + this.filter.title + '&category=' + this.filter.category + '&belongsTo=' + this.filter.belongsTo).then( response => {
                    this.articles = response.data.articles;

                } ).catch((error) => {
                    console.log(error);
                });
            },
            redirectToEditAritclePage(articleId) {
                window.location.href = this.$appBaseURL + "support/article/" + articleId + "/edit";   
            },
            deleteArticle(articleId) {
                this.$swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover it back!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it'
                    }).then((result) => {
                        if (result.value) {

                            axios.get('support/article/'+ articleId +'/delete').then( response => {
                                this.$swal(
                                    'Deleted!',
                                    'Article has been deleted.',
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
                                'Your data is safe :)',
                                'error'
                            )
                        }
                    })
            }
        }
    }
</script>