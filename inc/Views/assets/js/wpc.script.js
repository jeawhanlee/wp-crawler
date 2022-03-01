const config = {
  base_url: "http://" + window.location.hostname + "/wp-json/wpcrawler/v1/",
};

let crawl = new Vue({
  el: "#wpc_app",

  data() {
    return {
      crawl: {
        result: [],
        last_crawl: "",
        base: "",
      },
      loading: true,
      feed: null,
    };
  },

  mounted() {
    this.fetchCrawlResults();
  },

  methods: {
    fetchCrawlResults() {
      this.feed = "";
      axios
        .get(config.base_url + "get_crawl_result")
        .then((res) => {
          this.loading = false;
          if (res.status === 200) {
            this.crawl.result = res.data.result;
            this.crawl.last_crawl = res.data.last_crawl;
            this.crawl.base = res.data.base;
          }
        })
        .catch((error) => {
          console.log(error.response.data.error);
        });
    },

    initCrawl() {
      this.loading = true;

      axios
        .get(config.base_url + "crawl")
        .then((res) => {
          if (res.status === 200) {
            this.fetchCrawlResults();
            this.feed = res.data.message;
          }
        })
        .catch((error) => {
          console.log(error.response.data.error);
        });
    },
  },
});
