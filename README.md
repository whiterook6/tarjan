#Tarjan

This is a small script for detecting cycles in a graph.

Input: an array of vertex-children lists: [[1,2,3], [5,6,7]] means vertex 0 points to vertices 1, 2, and 3, while vertex 1 points to vertices 5, 6, and 7.

Output: an array of cycles: [[2,3,5,2], [5,6,5], [3,7,9,3]] contains three cycles. The first goes from 2 to 3 to 5 back to 2.