<template>
  <div>
    <Breadcrumb :title="sliderId ? 'Edit Slider' : 'Add Slider'" :breadcrumb="breadcrumb" />
    
    <div class="card mb-4">
      <div class="card-header">
        {{ sliderId ? "Edit Slider" : "Add SLider" }}
      </div>
      <div class="card-body">
        <form @submit.prevent="handleSaveOrUpdateSlider">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="slider-title" class="form-label">Title <span class="text-danger ps-2">*</span></label>
                    <input v-model="form.title" @keydown="error.titleMessage=''" class="form-control" id="slider-title" type="text" placeholder="Enter Title" />
                    <span class="validation-msg" v-if="error.titleMessage!==''">{{ error.titleMessage }}</span>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Image <span class="text-danger ps-2">*</span></label>
                    <div v-if="previewUrl" class="mb-2">
                        <img :src="previewUrl" alt="Preview" class="img-fluid" />
                        <button class="btn btn-sm btn-outline-danger" @click="removeImage()">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <input
                        type="file"
                        class="form-control"
                        @change="handleImageUpload"
                        ref="fileInput"
                    />
                    <span class="validation-msg" v-if="error.imageMessage">{{ error.imageMessage }}</span>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="slider-caption" class="form-label">Caption</label>
                    <textarea v-model="form.caption" class="form-control" id="slider-caption"></textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="slider-url" class="form-label">URL</label>
                    <input v-model="form.url" class="form-control" id="slider-url" type="text" min="1" placeholder="https://test.com" />
                </div>

                <div class="col-md-6 mb-3">
                    <label for="slider-order" class="form-label">Order Number</label>
                    <input v-model="form.order" class="form-control" id="slider-order" type="number" min="1" placeholder="Enter Slider Order Number" />
                </div>

                <div class="form-check mb-3">
                    <input v-model="form.status" class="form-check-input" id="slider-status" type="checkbox" checked />
                    <label class="form-check-label" for="slider-status">Active</label>
                </div>
                <div class="d-grid justify-content-md-end">
                    <button type="submit" class="btn btn-primary">{{ sliderId ? "Update" : "Save" }}</button>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import Breadcrumb from "../../components/admin/AdminBreadcrumb.vue";
import { saveSlider, getSlider, updateSlider } from '@/services/slider';

export default {
    components: {
        Breadcrumb,
    },
    data() {
        return {
            sliderId: this.$route.params.id || null,
            title: "",
            breadcrumb: [
                { name: "Admin", path: "/admin" },
                { name: "Sliders", path: "/admin/sliders" },
            ],
            // Form Fields
            form: {
                title: '',
                caption: '',
                url: '',
                order: 1,
                status: true
            },
            image: null,
            media_id: null,
            previewUrl: null,
            error: {
                titleMessage: '',
                imageMessage: ''
            },
        };
    },
    mounted() {
      if(this.sliderId != null) {
          console.log('yess');
        this.editSlider(this.sliderId)
      }
    },

    methods: {
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.image = file;
                this.previewUrl = URL.createObjectURL(file);
                this.media_id = null; // Clear existing media_id if new image uploaded
                this.error.imageMessage = ''; // clear error on new upload

            }
        },
        removeImage() {
            this.image = null;
            this.previewUrl = null;
            this.media_id = null;
            // Clear the input field file
            const inputRef = this.$refs.fileInput;
            if (inputRef) {
                inputRef.value = "";
            }
        },
        editSlider(id) {
            getSlider(id)
            .then((response) => {
                if (response.data.code === 200) {
                    const data = response.data.content;
                    this.form.title = data.title;
                    this.form.caption = data.caption ?? '';
                    this.form.url = data.url ?? '';
                    this.form.order = data.order;
                    this.form.status = data.status;

                    if (data.media) {
                        this.media_id = data.media.id;
                        this.previewUrl = `${process.env.VUE_APP_ASSET_BASE_URL}/storage/${data.media.thumbnail_path}`;
                    }
                }
            })
            .catch((e) => {
                console.log('Failed to fetch slider', e);
            });
        },
        handleSaveOrUpdateSlider() {
            if(this.validateForm()) {
                const formData = new FormData();
                formData.append('title', this.form.title);
                formData.append('caption', this.form.caption);
                formData.append('url', this.form.url);
                formData.append('order', this.form.order);
                formData.append('status', this.form.status ? 1 : 0);

                if (this.image) {
                    formData.append('image', this.image);
                } else if (this.media_id) {
                    formData.append('media_id', this.media_id);
                }


                if (this.sliderId) {
                    updateSlider(this.sliderId, formData)
                    .then((response) => {
                        if (response.data.code == 200) {
                            this.$router.push('/admin/sliders');
                        } else {
                            alert(response.data.message);
                        }
                    })
                    .catch(err => {
                        console.error('Failed to update page:', err);
                        alert(err);
                    });
                } else {
                    saveSlider(formData)
                    .then((response) => {
                        if (response.data.code == 200) {
                            this.$router.push('/admin/sliders');
                        } else {
                            alert(response.data.message);
                        }
                    })
                    .catch(err => {
                        console.error('Failed to save page:', err);
                        alert(err);
                    });
                }
            }
        },
        validateForm() {
            let valid = true;
            if (!this.form.title) {
                this.error.titleMessage = "Title is required.";
                valid = false;
            } else {
                this.error.titleMessage = '';
            }

            // Image validation (only for create, not update)
            if (!this.image && !this.media_id && !this.sliderId) {
                this.error.imageMessage = "Image is required.";
                valid = false;
            } else {
                this.error.imageMessage = '';
            }
            
            return valid;
        }
    },
};
</script>

<style scoped>
  .card-header {
    background-color: #f8f9fa;
  }
</style>
