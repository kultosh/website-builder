<template>
  <div>
    <Breadcrumb :title="'Settings'" :breadcrumb="breadcrumb" />
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div>
          <i class="fas fa-cog me-1"></i>
          Settings
        </div>
      </div>

      <div class="card-body">
        <div v-for="(value, key) in settingsForm" :key="key">
          <div v-if="key=='logo'" class="row">
            <div class="col-md-3">
              <label for="settings-logo">{{ key }} <span class="ms-1">:</span></label>
            </div>
            <div class="col-md-6 mb-3">
              <div class="row">
                <div class="col-md-8">
                    <input
                      type="file"
                      class="form-control"
                      @change="handleImageUpload($event, key)"
                      ref="fileInput"
                    />
                </div>
                <div v-if="previewUrl" class="col-md-4">
                    <img :src="previewUrl" alt="Preview" class="img-fluid" />
                </div>
              </div>
            </div>
          </div>

          <div v-else-if="key=='theme'" class="row">
            <div class="col-md-3">
              <label for="settings-themes">{{ key }} <span class="ms-1">:</span></label>
            </div>
            <div class="col-md-6 mb-3">
              <select
                id="website-themes"
                class="form-select"
                v-model="settingsForm[key]"
              >
                <option
                  v-for="(theme, index) in themesOption"
                  :key="index"
                  :value="theme.value"
                >
                  {{ theme.text }}
                </option>
              </select>
            </div>
          </div>

          <div v-else class="row">
            <div class="col-md-3">
              <label for="settings-objects">{{ key }} <span class="ms-1">:</span></label>
            </div>
            <div class="col-md-6 mb-3">
              <input type="text" v-model="settingsForm[key]" />
            </div>
          </div>
        </div>
      </div>

      <div class="card-footer">
        <div class="d-grid justify-content-md-end">
          <button type="button" class="btn btn-primary" @click="saveSettings">
            Save
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Breadcrumb from "../../components/admin/AdminBreadcrumb.vue";
import { getAllSettings, updateSettings } from "@/services/setting";

export default {
  components: {
    Breadcrumb,
  },
  data() {
    return {
      breadcrumb: [{ name: "Admin", path: "/admin" }],
      settingsForm: [],
      previewUrl: null,
      themesOption: [
        { text: "Primary", value: "bg-primary" },
        { text: "Secondary", value: "bg-secondary" },
        { text: "Success", value: "bg-success" },
        { text: "Danger", value: "bg-danger" },
        { text: "Warning", value: "bg-warning" },
        { text: "Info", value: "bg-info" },
        { text: "Dark", value: "bg-dark" },
      ],
    };
  },
  created() {
    this.fetchSettings();
  },
  methods: {
    handleImageUpload(event, settingKey) {
      const file = event.target.files[0];
      if (file) {
        this.previewUrl = URL.createObjectURL(file);
        this.settingsForm[settingKey] = file;
      }
    },
    getImageUrl(path) {
        if(path) {
            const baseUrl = process.env.VUE_APP_ASSET_BASE_URL;
            return `${baseUrl}/storage/${path}`;
        }
        return null;
    },
    fetchSettings() {
      getAllSettings()
        .then((response) => {
          const data = response.data;
          if (data.code == 200) {
            this.settingsForm = data.content;
            console.log('data>>', data.content);
            this.previewUrl =  this.getImageUrl(data.content.logo);
          } else {
            alert(data.message);
          }
        })
        .catch((err) => {
          console.error("Failed To List Settings:", err);
          alert(err);
        });
    },
    saveSettings() {
      const formData = new FormData();
      Object.entries(this.settingsForm).forEach(([key, value]) => {
        if (value instanceof File) {
          formData.append(key, value);
        } else {
          formData.append(key, value);
        }
      });

      updateSettings(formData)
        .then((response) => {
          if (response.data.code == 200) {
            alert("Settings updated successfully!");
          } else {
            alert(response.data.message);
          }
        })
        .catch((err) => {
          console.error("Error saving settings:", err);
          alert("Error saving settings");
        });
    },
  },
};
</script>
