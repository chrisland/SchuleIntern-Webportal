<template>
  <div class="folders flex-2">

    <div class="toolbar">
      <button class="btn btn-blau"
          @click="clickHandlerNewMessage()">Neue Nachricht</button>
    </div>
    
    <ul>
      <li v-bind:key="index" v-for="(item, index) in folders">
        <button 
          @click="clickHandlerFolder(item)"
          class="btn btn-grau text-align-left"
          :class="{
            'btn-orange' : foldersOpen == item.folderName,
            'margin-b-m' : item.folderName == 'Papierkorb'
          }" >

          <i v-if="item.folderName == 'Posteingang'" class="fa fa-inbox"></i>
          <i v-if="item.folderName == 'Gesendete'" class="fa fa-envelope"></i>
          <i v-if="item.folderName == 'Archiv'" class="fa fa-archive"></i>
          <i v-if="item.folderName == 'Papierkorb'" class="fa fa-trash"></i>

          {{item.folderName}}
        </button>
      </li>
    </ul>

    <button v-on:click="clickHandlerSettingsOpen()">Settings</button>

  </div>
</template>

<script>
export default {
  name: 'Folders',
  props: {
    folders: Object
  },
  data: function () {
    return {
      foldersOpen: 'Posteingang'
    }
  },
  created: function () {

    EventBus.$emit('folders--get', {});

  },
  methods: {

    clickHandlerSettingsOpen: function () {
      
      EventBus.$emit('settings--open', {});
    },
    
    clickHandlerNewMessage: function () {

      EventBus.$emit('message--form', {
        //folder: item,
      })

    },

    clickHandlerFolder: function (item) {

      EventBus.$emit('messages--changeFolder', {
        folder: item,
      })

      this.foldersOpen = item.folderName;

    }

  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
