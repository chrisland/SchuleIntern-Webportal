<template>
  <div class="folders flex-2">
    <ul>
      <li v-bind:key="index" v-for="(item, index) in folders">
        <button class=""
          @click="clickHandler(item)"
          :class="{
            'btn-primary' : item.folderName == 'Posteingang',
            'btn-success' : item.folderName == 'Gesendete',
            'btn-outline' : item.isStandardFolder == false
          }" >
          {{item.folderName}}
        </button>
      </li>
    </ul>

  </div>
</template>

<script>
export default {
  name: 'Folders',
  props: {
    folders: Object
  },
  created: function () {

    EventBus.$emit('folders--get', {});

  },
  methods: {

    clickHandler: function (item) {

      EventBus.$emit('messages--changeFolder', {
        folder: item,
      })

    }

  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
