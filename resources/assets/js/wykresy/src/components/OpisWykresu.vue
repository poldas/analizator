<template>
  <textarea :value="itemValue" @input="change" cols="90" rows="5"></textarea>
</template>
<script type="text/babel">
  export default {
    props: ['value'],
    data () {
      return {
        itemValue: ''
      }
    },
    watch: {
      value(value) {
        this.itemValue = value
      }
    },
    mounted () {
      this.itemValue = this.value
    },
    methods: {
      change(e) {
        this.itemValue = e.target.value
        this.$emit('input', this.itemValue)
      }
    }
  }
</script>
