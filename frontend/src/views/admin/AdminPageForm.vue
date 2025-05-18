<template>
  <div>
    <Breadcrumb :title="pageId ? 'Edit Page' : 'Add Page'" :breadcrumb="breadcrumb" />
    
    <div class="card mb-4">
      <div class="card-header">
        {{ pageId ? "Edit Page" : "Add Page" }}
      </div>
      <div class="card-body">
        <form @submit.prevent="handleSaveOrUpdatePage">
          <div class="row">
            <div class="col-md-6 mb-3">
                <label for="page-type" class="form-label">Make Parent <span class="text-danger ps-2">*</span></label>
                <select v-model="form.is_parent" class="form-select" aria-label="Default select example">
                    <option
                      v-for="option in yesNoOptions"
                      :key="option.value"
                      :value="option.value"
                    >
                      {{ option.label }}
                    </option>
                </select>
            </div>
            <div class="col-md-6 mb-3" v-if="!form.is_parent">
                <label for="page-type" class="form-label">Parent Page</label>
                <select v-model="form.parent_id" class="form-select">
                  <option
                    v-for="page in parentPages"
                    :key="page.id"
                    :value="page.id"
                  >
                    {{ page.title }}
                  </option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="page-type" class="form-label">Page Type <span class="text-danger ps-2">*</span></label>
                <select v-model="form.page_type" class="form-select">
                  <option
                    v-for="option in pageTypeOptions"
                    :key="option.value"
                    :value="option.value"
                  >
                    {{ option.label }}
                  </option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="page-title" class="form-label">Title <span class="text-danger ps-2">*</span></label>
                <input v-model="form.title" @keydown="error.titleMessage=''" class="form-control" id="page-title" type="text" placeholder="Enter Title" />
                <span class="validation-msg" v-if="error.titleMessage!==''">{{ error.titleMessage }}</span>
            </div>

            <div class="col-md-6 mb-3">
                <label for="page-title" class="form-label">Order Number</label>
                <input v-model="form.order" class="form-control" id="page-title" type="number" min="1" placeholder="Enter Page Order Number" />
            </div>
            <div class="col-md-6 mb-3">
                <label for="page-meta-title" class="form-label">Meta Title</label>
                <input v-model="form.meta_title" class="form-control" id="page-meta-title" type="text" placeholder="Enter Title" />
            </div>

            <div :class="'mb-3 '+(form.is_parent ? 'col-md-6' : 'col-md-12')">
                <label for="page-meta-description" class="form-label">Meta Description</label>
                <textarea v-model="form.meta_description" class="form-control" id="page-meta-description"></textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label for="page-type" class="form-label">Add To Menu</label>
                <select v-model="form.add_to_menu" class="form-select" aria-label="Default select example">
                    <option
                      v-for="option in yesNoOptions"
                      :key="option.value"
                      :value="option.value"
                    >
                      {{ option.label }}
                    </option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="page-type" class="form-label">Add To Home</label>
                <select v-model="form.add_to_home" class="form-select" aria-label="Default select example">
                    <option
                      v-for="option in yesNoOptions"
                      :key="option.value"
                      :value="option.value"
                    >
                      {{ option.label }}
                    </option>
                </select>
            </div>
          </div>

          <div class="row mb-3" v-if="!form.is_parent">
              <div class="d-flex justify-content-md-end mb-3">
                  <button type="button" class="btn btn-primary btn-sm" @click="addNewSection">Add Section</button>
              </div>
              <!-- Section: Accordion -->
              <AdminPageSection ref="sectionComponent" :sectionList="sectionList" />
          </div>
          <div class="form-check mb-3">
              <input v-model="form.status" class="form-check-input" id="page-status" type="checkbox" checked />
              <label class="form-check-label" for="page-status">Active</label>
          </div>
          <div class="d-grid justify-content-md-end">
              <button type="submit" class="btn btn-primary">{{ pageId ? "Update" : "Save" }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import Breadcrumb from "../../components/admin/AdminBreadcrumb.vue";
import AdminPageSection from '../../components/admin/AdminPageSection.vue';
import { savePage, getParentPages, getPage, updatePage } from '@/services/page';

export default {
    components: {
        Breadcrumb,
        AdminPageSection,
    },
    data() {
        return {
          pageId: this.$route.params.id || null,
          title: "",
          breadcrumb: [
              { name: "Admin", path: "/admin" },
              { name: "Pages", path: "/admin/pages" },
          ],
          // Form Fields
          form: {
            parent_id: null,
            title: '',
            meta_title: '',
            meta_description: '',
            order: 1,
            is_parent: 0,
            page_type: 'standard',
            add_to_menu: 0,
            add_to_home: 0,
            status: true
          },
          sectionList: [],
          parentPages: [],
          yesNoOptions: [
              { label: 'Yes', value: 1 },
              { label: 'No', value: 0 }
          ],
          pageTypeOptions: [
              { label: 'Standard', value: 'standard' },
              { label: 'Contact', value: 'contact' }
          ],
          error: {
            titleMessage: '',
          },
        };
    },
    mounted() {
      this.fetchParentPages();
      if(this.pageId!=null) {
        this.editPage(this.pageId)
      }
    },

    methods: {
      async fetchParentPages() {
        try {
          const response = await getParentPages();
          this.parentPages = response.data.content;
        } catch (error) {
          console.error("Failed to load parent pages", error);
        }
      },
      addNewSection() {
        this.$refs.sectionComponent.addSection();
      },
      editPage(params) {
        getPage(params)
        .then((response) => {
          if(response.data.code==200) {
            const data = response.data.content;
            console.log('data>>', data);
            this.form.parent_id = data.parent_id;
            this.form.title = data.title;
            this.form.meta_title = data.meta_title;
            this.form.meta_description = data.meta_description;
            this.form.order = data.order;
            this.form.is_parent = data.is_parent;
            this.form.page_type = data.page_type;
            this.form.add_to_menu = data.is_menu;
            this.form.add_to_home = data.add_to_home;
            this.form.status = data.status;
            this.sectionList = data.sections
          }
          
        })
        .catch((e) => {
          console.log('Something Wrong!', e);
        })
      },
      handleSaveOrUpdatePage() {
        const sectionComponent = this.$refs.sectionComponent;
        const sections = sectionComponent.getSections();
        if(this.validateForm()) {
          const formData = new FormData();

          // Append static fields
          formData.append('parent_id', this.form.parent_id);
          formData.append('title', this.form.title);
          formData.append('meta_title', this.form.meta_title);
          formData.append('meta_description', this.form.meta_description);
          formData.append('order', this.form.order);
          formData.append('is_parent', this.form.is_parent);
          formData.append('page_type', this.form.page_type);
          formData.append('add_to_menu', this.form.add_to_menu);
          formData.append('add_to_home', this.form.add_to_home);
          formData.append('status', this.form.status ? 1 : 0);

          // Append dynamic sections
          sections.forEach((section, index) => {
              formData.append(`sections[${index}][id]`, section.id);
              formData.append(`sections[${index}][layout]`, section.layout);
              formData.append(`sections[${index}][content]`, section.content);

              if (section.image) {
                  formData.append(`sections[${index}][image]`, section.image);
              } else if (section.media_id) {
                  formData.append(`sections[${index}][media_id]`, section.media_id);
              }
          });

          if (this.pageId) {
              updatePage(this.pageId, formData)
              .then((response) => {
                  if (response.data.code == 200) {
                      this.$router.push('/admin/pages');
                  } else {
                      alert(response.data.message);
                  }
              })
              .catch(err => {
                  console.error('Failed to update page:', err);
                  alert(err);
              });
          } else {
              savePage(formData)
              .then((response) => {
                  if (response.data.code == 200) {
                      this.$router.push('/admin/pages');
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

        const sectionComponent = this.$refs.sectionComponent;
        const sectionsAreValid = sectionComponent.validateSections();
        if (!sectionsAreValid) {
          valid = false;
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
