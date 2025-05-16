<template>
  <div>
    <Breadcrumb :title="pageId ? 'Edit Page' : 'Add Page'" :breadcrumb="breadcrumb" />
    
    <div class="card mb-4">
      <div class="card-header">
        {{ pageId ? "Edit Page" : "Add Page" }}
      </div>
      <div class="card-body">
        <form @submit.prevent="handleSavePage">
          <div class="row">
            <div class="col-md-6 mb-3">
                <label for="page-type" class="form-label">Make Parent</label>
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
                <label for="page-type" class="form-label">Page Type</label>
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
                <label for="page-title" class="form-label">Title</label>
                <input v-model="form.title" class="form-control" id="page-title" type="text" placeholder="Enter Title" />
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
                  <button type="button" class="btn btn-primary btn-sm" @click="addSection">Add Section</button>
              </div>
              <!-- Section: Accordion -->
              <div class="accordion" id="accordionPanelsStayOpenExample">
                <div
                  class="accordion-item mb-3"
                  :ref="`section-${index}`"
                  v-for="(section, index) in sections"
                  :key="section.id"
                >
                  <h2 class="accordion-header" :id="`heading-${index}`">
                    <div class="d-flex justify-content-between align-items-center w-100 bg-light">
                      <!-- Accordion toggle button -->
                      <button
                        class="accordion-button flex-grow-1 bg-transparent shadow-none"
                        type="button"
                        data-bs-toggle="collapse"
                        :data-bs-target="`#collapse-${index}`"
                        aria-expanded="true"
                        :aria-controls="`collapse-${index}`"
                      >
                        Section {{ index + 1 }}
                      </button>

                      <!-- Remove button (outside the toggle) -->
                      <button
                        v-if="index !== 0"
                        class="btn btn-danger btn-sm ms-2 me-3"
                        type="button"
                        @click="removeSection(index)"
                        title="Remove Section"
                      >
                        <i class="fa fa-trash"></i>
                      </button>
                    </div>
                  </h2>
                  <div
                    :id="`collapse-${index}`"
                    class="accordion-collapse collapse show"
                    :aria-labelledby="`heading-${index}`"
                  >
                    <div class="accordion-body">
                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label class="form-label">Layout Type</label>
                          <select v-model="section.layout" class="form-select">
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                          </select>
                        </div>
                        <div class="col-md-6 mb-3">
                          <label class="form-label">Image</label>
                          <input
                            type="file"
                            class="form-control"
                            @change="handleImageUpload($event, index)"
                          />
                        </div>
                        <div class="col-md-12">
                          <label class="form-label">Content</label>
                          <ckeditor
                            :editor="editor"
                            v-model="section.content"
                            :config="editorConfig"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
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
import CKEditor from '@ckeditor/ckeditor5-vue'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { savePage, getParentPages } from '@/services/page';

export default {
    components: {
        Breadcrumb,
        ckeditor: CKEditor.component
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
            title: 'Running',
            meta_title: '',
            meta_description: '',
            order: 1,
            is_parent: 0,
            page_type: 'standard',
            add_to_menu: 0,
            add_to_home: 0,
            status: true
          },
          parentPages: [],
          yesNoOptions: [
              { label: 'Yes', value: 1 },
              { label: 'No', value: 0 }
          ],
          pageTypeOptions: [
              { label: 'Standard', value: 'standard' },
              { label: 'Contact', value: 'contact' }
          ],
          // Add New Section
          sections: [
            {
              id: 1,
              layout: "1",
              image: null,
              content: ""
            }
          ],
          // start ckeditor settings
          editor: ClassicEditor,
          editorData: '',
          editorConfig: {
            toolbar: [
              "heading",
              "|",
              "bold",
              "italic",
              "link",
              "bulletedList",
              "numberedList",
              "|",
              "blockQuote",
              "insertTable",
              "mediaEmbed",
              "|",
              "undo",
              "redo",
            ],
          },
          // end ckeditor settings
        };
    },
    
    mounted() {
      this.fetchParentPages();
    },

    methods: {
      addSection() {
        const newId = this.sections.length + 1;
        this.sections.push({
          id: newId,
          layout: "1",
          image: null,
          content: ""
        });

        this.$nextTick(() => {
          const lastIndex = this.sections.length - 1;
          const newSectionRef = this.$refs[`section-${lastIndex}`];

          if (newSectionRef && newSectionRef[0]) {
            newSectionRef[0].scrollIntoView({ behavior: "smooth", block: "start" });
          }
        });

      },
      handleImageUpload(event, index) {
        const file = event.target.files[0];
        this.sections[index].image = file;
      },
      removeSection(index) {
        if (index === 0) return;
        if (confirm("Are you sure you want to remove this section?")) {
          this.sections.splice(index, 1);
        }
      },
      async fetchParentPages() {
        try {
          const response = await getParentPages();
          this.parentPages = response.data;
        } catch (error) {
          console.error("Failed to load parent pages", error);
        }
      },
      handleSavePage() {
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
        this.sections.forEach((section, index) => {
          formData.append(`sections[${index}][layout]`, section.layout);
          formData.append(`sections[${index}][content]`, section.content);
          if (section.image) {
            formData.append(`sections[${index}][image]`, section.image);
          }
        });

        savePage(formData)
        .then((response) => {
          console.log('response>>', response.status);
          if(response.data.code == 200) {
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
    },
};
</script>

<style scoped>
  .card-header {
    background-color: #f8f9fa;
  }
  /* Set the height of the CKEditor content area */
  ::v-deep(.ck-editor__editable) {
    min-height: 300px !important;
  }
</style>
