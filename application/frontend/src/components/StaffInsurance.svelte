<script>
    import { fade } from "svelte/transition";
    import { cubicInOut } from "svelte/easing";
    import { goto } from "$app/navigation";
    import { onMount } from "svelte";

    let insurances = [];
    onMount(() => {
        fetchInsurance(); // Fetch orders when the component is mounted
    });

    async function fetchInsurance() {
        try {
            const response = await fetch(
                "http://localhost:8080/database/Secretary/secretary_read_insurance_action.php",
                {
                    credentials: "include", // Include credentials (cookies) with the request
                },
            );
            const data = await response.json();
            console.log("Fetched data:", data); // Debugging: Log fetched data
            insurances = data.insurances;
            // console.log(insurances[0].InsuranceName); //debug purpose
            // console.log(testsCatalog[0].TestName); //debug purpose
        } catch (error) {
            console.error("Error fetching orders:", error);
        }
    }
    function getIconClass(insuranceName) {
        switch (insuranceName) {
            case "HealthPlus Insurance":
                return "fas fa-heartbeat text-red-400";
            case "FamilyCare Insurance":
                return "fas fa-users text-indigo-400 ";
            case "Senior Health Insurance":
                return "fas fa-user-nurse text-blue-400";
            case "Basic Health Insurance":
                return "fas fa-hospital text-gray-400";
            case "Elite Medical Insurance":
                return "fas fa-star text-yellow-400";
            case "Preventive Care Insurance":
                return "fas fa-syringe text-emerald-400";
            default:
                return "fas fa-question-circle";
        }
    }
    var filter = "all";
</script>

<div class="flex flex-col mt-8">
    <div class="card-container">
        {#each insurances.slice(0, 6) as insurance}
            <div class="card ">
                <i class="icon {getIconClass(insurance.InsuranceName)}"></i>
                <div class="card-header">{insurance.InsuranceName}</div>
                <div class="card-body">{insurance.InsuranceDetails}</div>
            </div>
        {/each}
    </div>
</div>

<style>
    .card-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem; /* Increased gap for more spacing */
    }
    .card {
        line-height: 2;
        background-color: white;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition:
            transform 0.2s,
            box-shadow 0.2s;
        position: relative;
        text-align: center;
        height: 300px; /* Increased height */
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .card-header {
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    .card-body {
        margin-bottom: 0.5rem;
    }
    .card-footer {
        margin-top: 0.5rem;
    }
    .icon {
        font-size: 5rem;
        margin-bottom: 1rem;
    }
</style>
