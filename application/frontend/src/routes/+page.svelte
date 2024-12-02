<script>
    import Hero from "../components/Hero.svelte";
    import {fade} from "svelte/transition";
    import { cubicInOut } from "svelte/easing";

</script>

<main
    class="flex flex-col"
    in:fade={{ delay: 200, duration: 200 }}
    out:fade={{ duration: 200, easing: cubicInOut }}
>
    <Hero />
    <!-- <Product/> -->
</main>
