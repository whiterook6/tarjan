<?php

namespace whiterook6\tarjan;

/**
 * This class implements an algorithm for finding cycles in a directed graph called the Tarjan Algorithm.
 * To use, create an instance of the class and hit run.
 * 
 * $tarjan = new Tarjan(); $tarjan->run($graph)
 *
 * @see Tarjan::run($graph)
 */
class Tarjan {

	/**
	 * Given a graph of node-adjacencies, find all cycles.
	 *
	 * @param $graph an array of vertex-children lists:
	 *               [[1,2,3], [5,6,7]] means vertex 0 points to vertices 1, 2, and 3
	 *               vertex 1 points to vertices 5, 6, 7.
	 *
	 * @return an array of cycles: [[2,3,5,2], [5,6,5], [3,7,9,3]] contains three cycles. The first goes from 2 to 3 to 5 back to 2.
	 */
	public function run($graph){
		$this->graph = $graph;

		// set up the "global" variables that are tracked between the recursive iterations of the algorithm
		$this->cycles = [];	// resulting cycles
		$this->marked = [];
		foreach (array_keys($graph) as $index ) {
			$this->marked[$index] = false;
		}
		$this->marked_stack = []; // keep a stack of what's been marked
		$this->point_stack = []; // keep a stack of the current cycle attempts -- those connected to 

		// Run a depth-first search on all vertices in the graph
		foreach ($graph as $index => $_) {

			$this->recurse($index, $index);

			while (count($this->marked_stack)){
				$this->marked[array_pop($this->marked_stack)] = false;
			}
		}

		// collect cycles. The tarjan algorithm outputs a cycle of three vertices as 4, 5, 6; we want 4, 5, 6, 4 (ie append the start
		// to the loop)
		$filtered_cycles = []; 
		foreach ($this->cycles as $cycle) {
			$cycle[] = $cycle[0];
			$filtered_cycles[] = $cycle;
		}
		return $filtered_cycles;
	}

	/**
	 * @param $index the index of the vertex being explored. Doesn't change during recursion.
	 *
	 * @param $oldest the the oldest ancestor we can find for this vertex through DFS. Finding an older
	 *                vertex already visited means we've found a cycle.
	 */
	private function recurse($index, $oldest){
		$cycle_found = false;

		// begin collecting whether vertices have been visited. Point stacks are the current cycle.
		$this->point_stack[] = $oldest;
		$this->marked[$oldest] = true;
		$this->marked_stack[] = $oldest;

		foreach ($this->graph[$oldest] as $child) { // for each vertex-index reachable by this vertex
			if ($child < $index){
				$this->graph[$child] = [];
			} else if ($child === $index){ 
				$this->cycles[] = $this->point_stack;
				$cycle_found = true;
			} else if ($this->marked[$child] === false){ // continue exploring down the graph, forward, to find visited nodes
				if ($this->recurse($index, $child)){
					$cycle_found = true;
				}
			}
		}

		if ($cycle_found){
			while (end($this->marked_stack) != $oldest){
				$this->marked[array_pop($this->marked_stack)] = false;
			}

			array_pop($this->marked_stack);
			$this->marked[$oldest] = false;
		}

		array_pop($this->point_stack);
		return $cycle_found;
	}
}
