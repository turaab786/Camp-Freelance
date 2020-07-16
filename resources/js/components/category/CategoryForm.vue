<template>
    <div class="modal fade text-left" id="categoryFormModal" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel33" aria-hidden="true">
                 <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                     <div class="modal-content">
                         <div class="modal-header">
                             <h4 class="modal-title" id="myModalLabel33">Category Form </h4>
                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 <span aria-hidden="true">&times;</span>
                             </button>
                         </div>
                         <form action="#" @submit.prevent="addNewCategory">
                             <div class="modal-body">
                                 <label>Name: </label>
                                 <div class="form-group">
                                     <input ref="name" type="text" placeholder="Category Name" class="form-control" :class="{ 'is-invalid': submitted && $v.category.name.$error }" v-model="category.name">
                                    <div v-if="submitted && !$v.category.name.required" class="invalid-feedback">Name is required</div>
                                    <div v-if="submitted && !$v.category.name.alpha" class="invalid-feedback">Name should be only aplhabets</div>
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
                    dropdownParent: '#categoryFormModal',
                    escapeMarkup: function (markup) {
                        return markup;
                    }
                }
            }    
        },
        created() {
            this.populateDropdowns();
        },
        validations: {
            category: {
                name: { required },
                icon: { required },
                parent: { required }
            }
        },
        methods: {
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
            addNewCategory(){
                this.submitted = true;
                this.spinner = true;

                //stop here if form is invalid
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }
                axios.post('support/category/store', this.category).then( response => {
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
                            message += '<li>' + value[0] + '</li>';
                        } 
                    }

                    message += '</ul>';
                    this.$toastr.error(message, 'Oops', {timeOut: 5000, escapeHtml: false});
                   
                });
            }
        }
    }
</script>