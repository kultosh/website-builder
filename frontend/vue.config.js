const { defineConfig } = require('@vue/cli-service')
module.exports = defineConfig({
  transpileDependencies: true,

  chainWebpack: config => {
    config
      .plugin('html')
      .tap(args => {
        args[0].title = process.env.VUE_APP_TITLE || 'Website Builder'
        return args
      })
  }
})
