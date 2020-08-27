<template>
  <div class="form flex-9">
    
    <button v-on:click="clickHandlerSettingsClose()">close</button>

    <form ref="form" @submit="checkForm"
      action="index.php?page=MessageCompose&action=send"
      method="post" enctype="multipart/form-data" id="composeForm">

    <ul>
      <li class="flex-row margin-b-m">
        <label class="flex-1">Betreff:</label>
        <input class="flex-6" type="text" v-model="messageSubject" />
      </li>
      <li class="flex-row">
        <label class="flex-1">Nachricht:</label>
        <textarea class="flex-6" v-model="messageText"></textarea>
      </li>
    </ul>


    

  </div>
</template>

<script>


export default {
  name: 'Form',
  components: {
    
  },
  props: {
  },
  data: function () {
    return {

    }
  },
  computed: {
    
  },
  created: function () {

  },
  methods: {

    clickHandlerSettingsClose: function () {

      EventBus.$emit('settings--close', {});
      
    }

  }
}
</script>

<style scoped>


</style>
