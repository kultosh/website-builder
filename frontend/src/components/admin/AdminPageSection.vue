<template>
    <div class="accordion" id="accordionPanelsStayOpenExample">
        <div
            class="accordion-item mb-3"
            :ref="`section-${index}`"
            v-for="(section, index) in sections"
            :key="section.id"
        >
            <h2 class="accordion-header" :id="`heading-${index}`">
                <div
                class="d-flex justify-content-between align-items-center w-100 bg-light"
                >
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
                            <!-- Show existing image preview if available -->
                            <div v-if="section.previewUrl" class="mb-2">
                                <img :src="section.previewUrl" alt="Preview" class="img-fluid" />
                                <button class="btn btn-sm btn-outline-danger" @click="removeImage(index)"><i class="fa fa-trash"></i></button>
                            </div>
                            <input
                                type="file"
                                class="form-control"
                                @change="handleImageUpload($event, index)"
                                :ref="`fileInput-${index}`"
                            />
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Content</label>
                            <span class="text-danger ps-2">*</span>
                            <ckeditor
                                :editor="editor"
                                v-model="section.content"
                                :config="editorConfig"
                                @input="clearContentError(index)"
                            />
                            <span class="validation-msg" v-if="section.validationError">{{ section.validationError }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CKEditor from '@ckeditor/ckeditor5-vue'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
    props: {
        sectionList: {
            type: Array,
            default: () => []
        }
    },
    components: {
        ckeditor: CKEditor.component
    },
    data() {
        return {
          // Add New Section
          sections: [
            {
              id: 1,
              layout: "1",
              image: null,
              content: "",
              media_id: null,
              previewUrl: null,
              validationError: '',
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
    methods: {
        getSections() {
            return this.sections.map(section => ({
                id: section.id,
                layout: section.layout,
                content: section.content,
                image: section.image || null,
                media_id: section.media_id || null
            }));
        },
        addSection() {
            this.sections.push({
                id: null,
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
            if (file) {
                this.sections[index].image = file;
                this.sections[index].previewUrl = URL.createObjectURL(file);
            }
        },
        removeSection(index) {
            if (index === 0) return;

            if (confirm("Are you sure you want to remove this section?")) {
                this.sections.splice(index, 1);
            }
        },
        getImageUrl(path) {
            if(path) {
                const baseUrl = process.env.VUE_APP_ASSET_BASE_URL;
                return `${baseUrl}/storage/${path}`;
            }
            return null;
        },
        removeImage(index) {
            this.sections[index].image = null;
            this.sections[index].previewUrl = null;
            this.sections[index].media_id = null;
            // Clear the input field file
            const inputRef = this.$refs[`fileInput-${index}`];
            if (inputRef && inputRef[0]) {
                inputRef[0].value = "";
            }
        },
        validateSections() {
            let isValid = true;

            this.sections.forEach((section, index) => {
                const plainText = section.content.replace(/<[^>]*>/g, '').trim();
                if (plainText.length < 3) {
                    this.sections[index].validationError = 'Content must be at least 3 characters long.';
                    isValid = false;
                } else {
                    this.sections[index].validationError = '';
                }
            });

            return isValid;
        },
        clearContentError(index) {
            if (this.sections[index].content && this.sections[index].content.trim() !== '') {
                this.sections[index].validationError = '';
            }
        }
    },
    watch: {
        sectionList: {
            handler(section) {
                if (Array.isArray(section) && section.length > 0) {
                    this.sections = section.map((sectionData) => {
                        return {
                            id: sectionData.id,
                            layout: sectionData.layout_type,
                            content: sectionData.description || "",
                            image: null,
                            media_id: sectionData.media?.id || null,
                            previewUrl: this.getImageUrl(sectionData.media?.thumbnail_path)
                        };
                    });
                }
            },
            immediate: true
        },
    }
};
</script>

<style scoped>
  /* Set the height of the CKEditor content area */
  ::v-deep(.ck-editor__editable) {
    min-height: 300px !important;
  }
</style>