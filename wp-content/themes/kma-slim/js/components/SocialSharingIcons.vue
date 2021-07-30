<template>
    <share-network
        :network="network"
        :url="url"
        :title="title"
        :description="description"
        :quote="quote"
    >
        <i :class="'fa fa-' + icon"></i>
		<span class="sr-only">share on {{ network }}</span>
    </share-network>
</template>
<script>
import { ShareNetwork } from 'vue-social-sharing'

export default {
    props: ['network','url','title','description','quote','icon'],
    components: { 'share-network': ShareNetwork }
}
</script>
