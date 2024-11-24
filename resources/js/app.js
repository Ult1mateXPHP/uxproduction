import './bootstrap';
import {createApp} from "vue";
import RootComponent from "./components/RootComponent.vue";

const root = createApp(RootComponent)
root.mount('#root')
