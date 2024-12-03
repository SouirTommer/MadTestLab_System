<script>
    import Hero from "../components/Hero.svelte";
    import {fade} from "svelte/transition";
    import { cubicInOut } from "svelte/easing";
    import { goto } from "$app/navigation";
    import { onMount } from "svelte";
    import { getCookie, handleLogout } from "../lib/api.js"; // Ensure the path includes .js


    onMount(() => {
        const username = getCookie('username');
        const role = getCookie('role');

        if (username && role) {
            switch (role) {
                case 'Patient':
                    goto("/patient");
                    break;
                case 'Secretary':
                    goto("/secretary");
                    break;
                case 'LabStaff':
                    goto("/labstaff");
                    break;
                default:
                    break;
            }
        }
    });
</script>

<main
    class="flex flex-col"
    in:fade={{ delay: 200, duration: 200 }}
    out:fade={{ duration: 200, easing: cubicInOut }}
>
    <Hero />
    <!-- <Product/> -->
</main>
