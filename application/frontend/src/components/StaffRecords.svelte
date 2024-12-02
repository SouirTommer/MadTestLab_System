<script>
    import { fade } from "svelte/transition";
    let orders = [
        {
            OrderID: "ORD001",
            PatientID: "PAT001",
            LabStaffID: "LAB001",
            SecretaryID: "SEC001",
            TestCode: "TC001",
            OrderDateTime: "2023-10-01 10:00",
            OrderStatus: "Pending",
        },
        {
            OrderID: "ORD002",
            PatientID: "PAT002",
            LabStaffID: "LAB002",
            SecretaryID: "SEC002",
            TestCode: "TC002",
            OrderDateTime: "2023-10-02 11:00",
            OrderStatus: "Completed",
        },
        {
            OrderID: "ORD003",
            PatientID: "PAT003",
            LabStaffID: "LAB003",
            SecretaryID: "SEC003",
            TestCode: "TC003",
            OrderDateTime: "2023-10-03 12:00",
            OrderStatus: "In Progress",
        },
    ];

    function getStatusClass(status) {
        switch (status) {
            case "Pending":
                return "bg-yellow-200 text-yellow-800";
            case "Completed":
                return "bg-green-200 text-green-800";
            case "In Progress":
                return "bg-blue-200 text-blue-800";
            default:
                return "bg-gray-200 text-gray-800";
        }
    }

    var filter = "all";
</script>

<div class="flex flex-col mt-8">
    <div class="flex gap-4 pb-4">
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'all'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transapraent text-slate-600'}"
            on:click={() => (filter = "all")}
        >
            All
        </button>
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'pending'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transapraent text-slate-600'}"
            on:click={() => (filter = "pending")}
        >
            Pending
        </button>
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'completed'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transapraent text-slate-600'}"
            on:click={() => (filter = "completed")}
        >
            Completed
        </button>
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'inprogress'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transapraent text-slate-600'}"
            on:click={() => (filter = "inprogress")}
        >
            In Progress
        </button>
    </div>
    <div>
        <table class=" bg-white border border-slate-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">OrderID</th>
                    <th class="py-2 px-4 border-b">LabStaffID</th>
                    <th class="py-2 px-4 border-b">SecretaryID</th>
                    <th class="py-2 px-4 border-b">TestCode</th>
                    <th class="py-2 px-4 border-b">OrderDateTime</th>
                    <th class="py-2 px-4 border-b">OrderStatus</th>
                    <th class="py-2 px-4 border-b">Details</th>
                </tr>
            </thead>
            <tbody>
                {#each orders as order}
                    <tr>
                        <td class="py-2 px-4 border-b">{order.OrderID}</td>
                        <td class="py-2 px-4 border-b">{order.LabStaffID}</td>
                        <td class="py-2 px-4 border-b">{order.SecretaryID}</td>
                        <td class="py-2 px-4 border-b">{order.TestCode}</td>
                        <td class="py-2 px-4 border-b">{order.OrderDateTime}</td
                        >
                        <td class="py-2 px-4 border-b">
                            <span
                                class="status-tag {getStatusClass(
                                    order.OrderStatus,
                                )}">{order.OrderStatus}</span
                            >
                        </td>
                        <td class="py-2 px-4 border-b">
                            <a href="" class="text-indigo-400 hover:underline">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                {/each}
            </tbody>
        </table>
    </div>
</div>
