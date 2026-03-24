// vite.config.js
import {
  defineConfig
} from "file:///D:/Laravel/Gem_autoprint/node_modules/vite/dist/node/index.js";
import laravel from "file:///D:/Laravel/Gem_autoprint/node_modules/laravel-vite-plugin/dist/index.js";
import vue from "file:///D:/Laravel/Gem_autoprint/node_modules/@vitejs/plugin-vue/dist/index.mjs";
var vite_config_default = defineConfig({
  plugins: [
    laravel({
      input: [
        "resources/js/Master/index.js",
        "resources/js/Master/index_login.js"
      ],
      refresh: true
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false
        }
      }
    })
  ],
  resolve: {
    alias: {
      vue: "vue/dist/vue.esm-bundler.js"
    }
  },
  build: {
    target: "esnext",
    sourcemap: false,
    cssCodeSplit: true,
    rollupOptions: {
      output: {
        manualChunks: {
          // if (id.includes('node_modules')) {
          //     return id.toString().split('node_modules/')[1].split('/')[0].toString();
          // };
          // if (
          //     id.includes('resurces/js/Page/welcome.vue') ||
          //     id.includes('resurces/js/Page/asset_register.vue') ||
          //     id.includes('resurces/js/Page/asset_register_add.vue') ||
          //     id.includes('resurces/js/Page/asset_register_edit.vue')
          // ) {
          //     return 'asset_register';
          // }
          vue: ["vue", "vue-router"],
          sweetalert: ["sweetalert2"],
          apexcharts: ["apexcharts", "vue3-apexcharts"],
          vendor: ["js-file-download", "vue3-carousel", "@chenfengyuan/vue-barcode", "lodash.debounce", "yup", "vee-validate"]
        }
      }
    }
  },
  esbuild: {
    drop: ["console", "debugger"]
    // optional, kecilkan bundle
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJEOlxcXFxMYXJhdmVsXFxcXEdlbV9hdXRvcHJpbnRcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZmlsZW5hbWUgPSBcIkQ6XFxcXExhcmF2ZWxcXFxcR2VtX2F1dG9wcmludFxcXFx2aXRlLmNvbmZpZy5qc1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9pbXBvcnRfbWV0YV91cmwgPSBcImZpbGU6Ly8vRDovTGFyYXZlbC9HZW1fYXV0b3ByaW50L3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHtcbiAgICBkZWZpbmVDb25maWdcbn0gZnJvbSAndml0ZSc7XG5pbXBvcnQgbGFyYXZlbCBmcm9tICdsYXJhdmVsLXZpdGUtcGx1Z2luJztcbmltcG9ydCB2dWUgZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlJztcblxuZXhwb3J0IGRlZmF1bHQgZGVmaW5lQ29uZmlnKHtcbiAgICBwbHVnaW5zOiBbXG4gICAgICAgIGxhcmF2ZWwoe1xuICAgICAgICAgICAgaW5wdXQ6IFtcbiAgICAgICAgICAgICAgICAncmVzb3VyY2VzL2pzL01hc3Rlci9pbmRleC5qcycsXG4gICAgICAgICAgICAgICAgJ3Jlc291cmNlcy9qcy9NYXN0ZXIvaW5kZXhfbG9naW4uanMnLFxuICAgICAgICAgICAgXSxcbiAgICAgICAgICAgIHJlZnJlc2g6IHRydWUsXG4gICAgICAgIH0pLFxuICAgICAgICB2dWUoe1xuICAgICAgICAgICAgdGVtcGxhdGU6IHtcbiAgICAgICAgICAgICAgICB0cmFuc2Zvcm1Bc3NldFVybHM6IHtcbiAgICAgICAgICAgICAgICAgICAgYmFzZTogbnVsbCxcbiAgICAgICAgICAgICAgICAgICAgaW5jbHVkZUFic29sdXRlOiBmYWxzZSxcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgfSxcbiAgICAgICAgfSksXG4gICAgXSxcbiAgICByZXNvbHZlOiB7XG4gICAgICAgIGFsaWFzOiB7XG4gICAgICAgICAgICB2dWU6ICd2dWUvZGlzdC92dWUuZXNtLWJ1bmRsZXIuanMnLFxuICAgICAgICB9LFxuICAgIH0sXG4gICAgYnVpbGQ6IHtcbiAgICAgICAgdGFyZ2V0OiAnZXNuZXh0JyxcbiAgICAgICAgc291cmNlbWFwOiBmYWxzZSxcbiAgICAgICAgY3NzQ29kZVNwbGl0OiB0cnVlLFxuICAgICAgICByb2xsdXBPcHRpb25zOiB7XG4gICAgICAgICAgICBvdXRwdXQ6IHtcbiAgICAgICAgICAgICAgICBtYW51YWxDaHVua3M6IHtcbiAgICAgICAgICAgICAgICAgICAgLy8gaWYgKGlkLmluY2x1ZGVzKCdub2RlX21vZHVsZXMnKSkge1xuICAgICAgICAgICAgICAgICAgICAvLyAgICAgcmV0dXJuIGlkLnRvU3RyaW5nKCkuc3BsaXQoJ25vZGVfbW9kdWxlcy8nKVsxXS5zcGxpdCgnLycpWzBdLnRvU3RyaW5nKCk7XG4gICAgICAgICAgICAgICAgICAgIC8vIH07XG4gICAgICAgICAgICAgICAgICAgIC8vIGlmIChcbiAgICAgICAgICAgICAgICAgICAgLy8gICAgIGlkLmluY2x1ZGVzKCdyZXN1cmNlcy9qcy9QYWdlL3dlbGNvbWUudnVlJykgfHxcbiAgICAgICAgICAgICAgICAgICAgLy8gICAgIGlkLmluY2x1ZGVzKCdyZXN1cmNlcy9qcy9QYWdlL2Fzc2V0X3JlZ2lzdGVyLnZ1ZScpIHx8XG4gICAgICAgICAgICAgICAgICAgIC8vICAgICBpZC5pbmNsdWRlcygncmVzdXJjZXMvanMvUGFnZS9hc3NldF9yZWdpc3Rlcl9hZGQudnVlJykgfHxcbiAgICAgICAgICAgICAgICAgICAgLy8gICAgIGlkLmluY2x1ZGVzKCdyZXN1cmNlcy9qcy9QYWdlL2Fzc2V0X3JlZ2lzdGVyX2VkaXQudnVlJylcbiAgICAgICAgICAgICAgICAgICAgLy8gKSB7XG4gICAgICAgICAgICAgICAgICAgIC8vICAgICByZXR1cm4gJ2Fzc2V0X3JlZ2lzdGVyJztcbiAgICAgICAgICAgICAgICAgICAgLy8gfVxuICAgICAgICAgICAgICAgICAgICB2dWU6IFtcInZ1ZVwiLCBcInZ1ZS1yb3V0ZXJcIl0sXG4gICAgICAgICAgICAgICAgICAgIHN3ZWV0YWxlcnQ6IFtcInN3ZWV0YWxlcnQyXCJdLFxuICAgICAgICAgICAgICAgICAgICBhcGV4Y2hhcnRzOiBbXCJhcGV4Y2hhcnRzXCIsIFwidnVlMy1hcGV4Y2hhcnRzXCJdLFxuICAgICAgICAgICAgICAgICAgICB2ZW5kb3I6IFsnanMtZmlsZS1kb3dubG9hZCcsICd2dWUzLWNhcm91c2VsJywgJ0BjaGVuZmVuZ3l1YW4vdnVlLWJhcmNvZGUnLCAnbG9kYXNoLmRlYm91bmNlJywgJ3l1cCcsICd2ZWUtdmFsaWRhdGUnXVxuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICB9LFxuICAgICAgICB9LFxuICAgIH0sXG4gICAgZXNidWlsZDoge1xuICAgICAgICBkcm9wOiBbXCJjb25zb2xlXCIsIFwiZGVidWdnZXJcIl0sIC8vIG9wdGlvbmFsLCBrZWNpbGthbiBidW5kbGVcbiAgICB9LFxufSk7XG4iXSwKICAibWFwcGluZ3MiOiAiO0FBQWdRO0FBQUEsRUFDNVA7QUFBQSxPQUNHO0FBQ1AsT0FBTyxhQUFhO0FBQ3BCLE9BQU8sU0FBUztBQUVoQixJQUFPLHNCQUFRLGFBQWE7QUFBQSxFQUN4QixTQUFTO0FBQUEsSUFDTCxRQUFRO0FBQUEsTUFDSixPQUFPO0FBQUEsUUFDSDtBQUFBLFFBQ0E7QUFBQSxNQUNKO0FBQUEsTUFDQSxTQUFTO0FBQUEsSUFDYixDQUFDO0FBQUEsSUFDRCxJQUFJO0FBQUEsTUFDQSxVQUFVO0FBQUEsUUFDTixvQkFBb0I7QUFBQSxVQUNoQixNQUFNO0FBQUEsVUFDTixpQkFBaUI7QUFBQSxRQUNyQjtBQUFBLE1BQ0o7QUFBQSxJQUNKLENBQUM7QUFBQSxFQUNMO0FBQUEsRUFDQSxTQUFTO0FBQUEsSUFDTCxPQUFPO0FBQUEsTUFDSCxLQUFLO0FBQUEsSUFDVDtBQUFBLEVBQ0o7QUFBQSxFQUNBLE9BQU87QUFBQSxJQUNILFFBQVE7QUFBQSxJQUNSLFdBQVc7QUFBQSxJQUNYLGNBQWM7QUFBQSxJQUNkLGVBQWU7QUFBQSxNQUNYLFFBQVE7QUFBQSxRQUNKLGNBQWM7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUEsVUFZVixLQUFLLENBQUMsT0FBTyxZQUFZO0FBQUEsVUFDekIsWUFBWSxDQUFDLGFBQWE7QUFBQSxVQUMxQixZQUFZLENBQUMsY0FBYyxpQkFBaUI7QUFBQSxVQUM1QyxRQUFRLENBQUMsb0JBQW9CLGlCQUFpQiw2QkFBNkIsbUJBQW1CLE9BQU8sY0FBYztBQUFBLFFBQ3ZIO0FBQUEsTUFDSjtBQUFBLElBQ0o7QUFBQSxFQUNKO0FBQUEsRUFDQSxTQUFTO0FBQUEsSUFDTCxNQUFNLENBQUMsV0FBVyxVQUFVO0FBQUE7QUFBQSxFQUNoQztBQUNKLENBQUM7IiwKICAibmFtZXMiOiBbXQp9Cg==
