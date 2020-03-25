<template>
  <div class="messages flex-9">
    <div class="toolbar flex-row"> 
      <div class="flex-9">
        <button
          class="btn-primary"
          @click="clickHandler()">Neue Nachricht</button>
        
      </div>
      <form id="search" class="3">
        <input type="search" name="query" v-model="searchQuery">
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
    messages: Array
  },
  data: function () {
    return {
      searchQuery: '',
      gridColumns: ['hasAttachment','priority','isRead','subject', 'senderConnect','timeFormat'],
      gridColumnsHeader: ['','','','Betreff', 'Sender','Datum']
    }
  },
  
  created: function () {

  },
  methods: {

    clickHandler: function () {

      EventBus.$emit('message--new', {
        //folder: item,
      })

    }

  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
