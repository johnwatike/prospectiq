// 📁 JS for follow_up_note

// Vanilla JS
function validateForm() {
  const required = document.querySelector('[name="name"]');
  if (required && !required.value) {
    alert("Field is required.");
  }
}

// Vue.js Sample
const app_follow_up_note = Vue.createApp({
  data() {
    return {
      rows: [],
      loading: false
    };
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    fetchData() {
      this.loading = true;
      fetch('/api/follow_up_note')
        .then(r => r.json())
        .then(data => {
          this.rows = data;
          this.loading = false;
        });
    }
  }
}).mount('#follow_up_note-app');
