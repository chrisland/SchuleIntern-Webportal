<template>
  <div class="messages flex-10">
    <div class="toolbar flex-row"> 
      <div class="flex-9 flex-row">

          <button v-if="activeFolder.folderName == 'Papierkorb'"
            v-on:click="messageDelete()" class="btn btn-red icon">
            <i class="fa fa-trash"></i>
          </button>
          <button v-else v-on:click="messageDelete()" class="btn btn-grau icon">
            <i class="fa fa-trash"></i>
          </button>

          <button v-on:click="" class="btn btn-grau">Gelesen</button>
          <button v-on:click="" class="btn btn-grau">Ungelesen</button>

          <div v-if="activeFolder.folderName != 'Gesendete'" class="flex-row" >
            <select v-model="messageMoveSelected">
              <option :value="null" disabled hidden>- Verschieben -</option>
              <option v-bind:key="index" v-for="(item, index) in foldersFilterMove"
                :value="item">{{item.folderName}}</option>
            </select>
            <button v-on:click="messageMove()" class="btn btn-grau">
              <i class="fa fa-arrow-right"></i>
            </button>
          </div>
        
      </div>
      <form id="search" class="3">
        <input type="search" name="query" v-model="searchQuery" placeholder="Suche...">
      </form>
    </div>

    <GridTemplate
      v-bind:list="messages"
      v-bind:columns="gridColumns"
      v-bind:columsHeader="gridColumnsHeader"
      v-bind:filter-key="searchQuery">
    </GridTemplate>

  </div>
</template>

<script>

import GridTemplate from './GridTemplate.vue'

export default {
  name: 'Messages',
  components: {
    GridTemplate
  },
  props: {
    messages: Array,
    folders: Array,
    foldersFilterMove: Array,
    moveFolders: Array,
    //messageMoveSelected: String,
    activeFolder: Array
  },
  data: function () {
    return {
      searchQuery: '',
      messageMoveSelected: null
      //gridColumns: ['hasAttachment','priority','isRead','subject', 'senderConnect','timeFormat'],
      //gridColumnsHeader: ['','','','Betreff', 'Sender','Datum']
    }
  },
  
  computed: {
    gridColumns: function () {

      if (this.activeFolder.folderName == "Gesendete") {
        return ['hasAttachment','priority','isRead','subject', 'recipients','timeFormat'];
      } else {
        return ['hasAttachment','priority','isRead','subject', 'senderConnect','timeFormat'];
      }
    },
    gridColumnsHeader: function () {

      if (this.activeFolder.folderName == "Gesendete") {
        return ['','','','Betreff', 'Empfänger','Datum'];
      } else {
        return ['','','','Betreff', 'Sender','Datum'];
      }
    }
  },

  created: function () {

    // console.log(this.folders);
    

  },
  methods: {

    messageMove: function () {

      EventBus.$emit('message--move', {
        toFolder: this.messageMoveSelected
      });

    },

    messageDelete: function () {

      EventBus.$emit('message--delete', {});

    }
    

  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
