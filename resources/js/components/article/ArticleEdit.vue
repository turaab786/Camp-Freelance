<template>
    <section class="basic-horizontal-layouts">
        <div class="row match-height">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-horizontal" action="#" @submit.prevent="editArticle">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" v-model="article.title" id="title" class="form-control" :class="{ 'is-invalid': submitted && $v.article.title.$error }"  name="title" placeholder="Title of Article">
                                                        <div v-if="submitted && !$v.article.title.required" class="invalid-feedback">Title is required</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" v-model="article.slug" id="slug" class="form-control" :class="{ 'is-invalid': submitted && $v.article.slug.$error }" name="slug" placeholder="Slug of Article">
                                                        <div v-if="submitted && !$v.article.slug.required" class="invalid-feedback">Slug is required</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="position-relative has-icon-left">
                                                <ckeditor v-model="article.description" :editor="article.editor" :config="article.editorConfig"></ckeditor>
                                                <div v-if="submitted && !$v.article.description.required" class="invalid-feedback">Description is required</div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="custom-control custom-switch switch-lg custom-switch-success mr-2 mb-1">
                                                <p class="mb-0">Belongs to</p>
                                                <toggle-button @change="setBelongsTo()" :sync="true" :disabled="false" :value="article.belongsTo" :width="70" :labels="{checked: 'Seller', unchecked: 'Buyer'}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-primary mr-1 mb-1">Submit</button>
                                            <button type="reset" class="btn btn-outline-warning mr-1 mb-1">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6>Categories</h6>
                        <div v-if="submitted && !$v.article.categories.required" class="alert alert-danger mt-1 alert-validation-msg" role="alert">
                            <span><strong>Category</strong> is required</span>
                        </div>
                        <div v-if="submitted && !$v.article.categories.maxLength" class="alert alert-danger mt-1 alert-validation-msg" role="alert">
                            <span>Maximum one category can be selected</span>
                        </div>
                        <div v-for="(category, key) in categories" :key="key" class="custom-control custom-checkbox">
                            <input type="checkbox" v-bind:checked="categoryId == category.id ? true : false" class="custom-control-input" :value="category.id" v-model="article.categories" @change="setCategories($event)" :name="'category' + category.id" :id="'category' + category.id" >
                            <label class="custom-control-label" :for="'category' + category.id">{{ category.name }}</label>

                            <!--<div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" checked name="customCheck" id="customCheck1">
                                <label class="custom-control-label" for="customCheck1">Active</label>
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        data() {
            return {
                submitted: false,
                categories: [],
                categoryId: 0,
                spinner: false,
                articleId: window.location.href.split('/').slice(-2).reverse().pop(),
                article: {
                    title: '',
                    description: '',
                    belongsTo: false,
                    belongsToType: 'buyer',
                    categories: [],
                    slug: '',
                    editor: ClassicEditor,
                    editorConfig: {
                        height: 400
                    }
                }
            };
        },
        validations: {
            article: {
                title: { required },
                description: { required },
                categories: {
                    required,
                    maxLength: maxLength(1)
                }
            }
        },
        created() {
            this.getArticle();
            this.populateCategories();
        },
        methods: {
            getArticle() {
                axios.get('support/article/'+ this.articleId +'/get').then( response => {
                    this.article.title = response.data.article.title;
                    this.article.description = response.data.article.content;
                    this.article.slug = response.data.article.slug;
                    this.categoryId = response.data.article.category_id;
                    this.article.categories[0] = this.categoryId;

                    if ( response.data.article.belongs_to === 'buyer' ) {
                        this.article.belongsTo = false;
                    } else {
                        this.article.belongsTo = true;
                    }

                    this.article.belongsToType = response.data.article.belongs_to;
                }).catch((error) => {
                    console.log(error);
                });
            },
            editArticle(){
                this.submitted = true;

                //stop here if form is invalid
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }

                axios.post('support/article/' + this.articleId + '/update', this.article).then( response => {
                    this.spinner = false;
                    this.$toastr.success(response.data.message, 'Success', {timeOut: 5000, escapeHtml: false});
                    window.location.href = this.$appBaseURL + "support-article";                
                } ).catch((error) => {
                    this.spinner = false;
                    let message = error.response.data.message;
                    message += '<ul>';
                    let errors = error.response.data.errors;

                    for (const [key, value] of Object.entries(errors)) {
                        if (value[0] !== '') {
                            message += '<li>' + value[0] + '</li>';
                        } 
                    }

                    message += '</ul>';
                    this.$toastr.error(message, 'Oops', {timeOut: 5000000, escapeHtml: false});
                   
                });
            },
            setBelongsTo() {
                if ( this.article.belongsTo == true ) {
                    this.article.belongsTo = false;
                    this.article.belongsToType = 'buyer';
                } else {
                    this.article.belongsTo = true;
                    this.article.belongsToType = 'seller';
                }
            },
            setCategories(e) {
                console.log(this.article.categories);
            },
            populateCategories() {
                //populating parent dropdown
                axios.get('support/category/children').then( response => {
                    this.categories = response.data.categories;
                    
                } ).catch((error) => {
                    console.log(error);
                });
            }
        }
    }
</script>