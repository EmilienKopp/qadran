<script lang="ts">
    import { Chart } from 'chart.js/auto';
    
    export let budget: number = 0;
    export let spent: number = 0;
    export let title: string;
    export let width: number | string = 600;
    export let height: number | string = 600;

    let canvas: HTMLCanvasElement;


    const data = {
        labels: ["spent", "budget(left)"],
        datasets: [{
            data:  [spent, budget - spent],
        }]
    };

    $: if(canvas) {
        const chart = new Chart(canvas, {
            type: 'pie',
            data: data,
        });
    }
</script>

<div class="modal-action flex justify-center h-96">
    <canvas bind:this={canvas} {width} {height}></canvas>
</div>

