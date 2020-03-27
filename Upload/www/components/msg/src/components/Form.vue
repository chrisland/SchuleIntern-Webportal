<template>
  <div class="form flex-9">
    <form action="index.php?page=MessageCompose"
      method="post" enctype="multipart/form-data" id="composeForm">
      
      <ul>
        <li>
          Empfänger:
          <button>add</button>
        </li>
        <li>
          Kopieempfänger cc:
          <button>add</button>
        </li>
        <li>
          Verdeckte Kopieempfänger bcc:
          <button>add</button>
        </li>
      </ul>

      betreff:
      <input type="text" name="betreff" />

      text:
      <textarea name="content"></textarea>


      <button  @click="clickHandlerSubmit()">Nachricht senden</button>
    
    </form>

  </div>
</template>

<script>

//import GridTemplate from './GridTemplate.vue'

export default {
  name: 'Form',
  components: {
    //GridTemplate
  },
  props: {
    //messages: Array
  },
  data: function () {
    return {
    }
  },
  
  created: function () {

  },
  methods: {

    clickHandlerSubmit: function () {
      console.log('...senden');
      /*
      if(currentRecipients.length == 0) {
        this.errorMsg = 'Es muss mindestens ein Empfänger angegeben sein!';
      } else {
        $("#composeForm").submit();
      }
      */
    }
 

  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
