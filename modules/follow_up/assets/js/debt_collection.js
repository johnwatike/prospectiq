// 📁 JS for debt_collection

// Vanilla JS
function validateForm() {
  const required = document.querySelector('[name="name"]');
  if (required && !required.value) {
    alert("Field is required.");
  }
}

// Vue.js Sample
if (typeof Vue !== 'undefined') {
    const app_debt_collection = Vue.createApp({
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
      fetch('/api/debt_collection')
        .then(r => r.json())
        .then(data => {
          this.rows = data;
          this.loading = false;
        });
    }
  }
}).mount('#debt_collection-app');
} else {
    console.error('Vue.js is not loaded. Please ensure Vue.js is loaded before this script.');
}