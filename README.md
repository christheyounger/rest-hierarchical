# Challenge: A RESTful API to serve a full tree of Hierarchical data

[![Build Status](https://travis-ci.com/darkbluesun/rest-hierarchical.svg?branch=master)](https://travis-ci.com/darkbluesun/rest-hierarchical)
[![codecov](https://codecov.io/gh/darkbluesun/rest-hierarchical/branch/master/graph/badge.svg)](https://codecov.io/gh/darkbluesun/rest-hierarchical)

This project aims to demonstrate an understanding of the challenges involved in
storing hierarchical data in a typical relational database (in this case SQLite),
and serving that data up in full via a RESTful API.

I will be using Symfony 4, doctrine, and FOS REST bundle and JMS serializer to
simplify things and to avoid re-inventing the wheel, however I will attempt to
handle the data structure myself to demonstrate proficiency.

Testing will be via PHPUnit, and docs available at /api/doc.

## Installation

1. `composer install` will install vendor libraries
2. `bin/console doctrine:database:create` will create the SQLite database.
3. `bin/console doctrine:migrations:migrate` will set up the database table.
4. `bin/console doctrine:fixtures:load` will load sample data into the database.

## Usage

`bin/console server:start` will start the web server running.

Visit http://localhost:8000/api/doc in your browser to view the docs, or
GET http://localhost:8000/api/stores to access the API directly.

Run `bin/phpunit` to run unit tests.

## The thought process

### First steps

Firstly I've created a MVP by implmenting the very familiar self-referencing
one-to-many self-referencing relationships. This helped me to complete the REST
API completely, and now I can move on to working the problem of retrieving
hierarchical data from a relational database efficiently.

Each Store can have a parent store, and conversely each store can have any number
of branch stores beneath it in the hierarchy. This is often refered to as the
Adjacency List model. At a small scale this is efficient enough and functional,
but it does not scale well.

### The problem

The problem is retrieving the entire graph from the database and presenting it
in a hierarchical format for the end user. Left to their own devices, the Serializer
and the ORM will traverse the graph by querying for branches of each Store that
it encounteres. This will inevitibly result in large numbers of queries when a
reasonable amount of data is added to a reasonable depth.

Adding 5 branches to each store, at a depth of 5 produces 3907 Database Queries
to build the graph. Obviously this is unacceptable.

Now if a specific depth were specified (and enforced) rather than an arbitrary
(or infinite) depth, then a query could be written easily enough to fetch all
the branches with their relationships in tact, however this one query suffers
from performance degredation, and complexity, the more levels that are added.

### Understanding of common solutions

To my knowledge there exist three common solutions to the problem of storing
hierarchical data in relational databases. Nested Set, Materialised path, and
Closure Table. Nested set stores left and right values for each element in the
hierarchy so that querying part of the hierarchy, and updating it, is inexpensive.
Materialised path stores a string representation of the path to the root node
as a field on the node. The Closure Table solution uses a sepearate table to
store all relationships to all of the ancestors of a given node, including itself.

### Choosing a solution

I have personally worked on Closure Table solutions in the past and I recognise
they are quite efficient and easy to query, however I would like to demonstrate
here my ability to learn a new thing by implmenting the Materialised Path solution.
I did also consider the Nested Set solution, and could implement that using a
helper library, but it turned out to be far too complex to implement myself.

This reduces the number of queries to equal to the number of root nodes plus one
to get the root nodes.

### Materialised Path

Each Store receives a string path containg it's ID. For example:

```
/1 the root store
/1/2 a branch of the root store
/1/3 another branch of the root store
/1/2/4 a third level branch
```

Querying for a slice of the hierarchy - all the descendants of a mid level
branch for example - is easy. Simply query by the path with a wildcard.

Removing all an node's descendants is simple enough also, and no other records
need to be affected by the deletion.

Reparenting an node is a fairly simple matter of recursively rewriting the
paths of all the child nodes (so long as we remember to populate the tree first!)

Interestingly, a limitation of this solution is that finding direct descendants
of a particular node is somewhat more difficult, however that was not a part of
the specification for this challenge.

Another limitation is of course the length of the path field. As IDs get larger
the possible depth of the hierarchy is reduced. The realistic maximum depth of
unencoded IDs is around 10, varying with the length of the IDs.

Encoding the IDs as base 36 and using fixed-width IDs instead of seperators
increases the possible depth to 50 and the max ID to 60466174. This is by no means
infinite, however it is quite generous. If this is still too limiting, I'd
suggest either using one of the other two solutions (Nested Set or Closure Table).

#### How I've implemented it

I've put queries relating to the tree structure (get root nodes, get branches,
delete branches) into TreeRepository.php. The queries are super-simple to
understand.

I've created a couple of traits for the path functionality and the tree
functionality.

TreeTrait's buildTree method simply associates child nodes with their parents.
This allows one parent to have a fully constructed hierarchy if provided with
all of it's descendants.

PathTrait's methods manage generating the new path for a node when changing
it's parent node. setChildOf is designed to wrap the existing setParent function
with the new functionality for setting a path.

Hopefully this is all fairly minimal, easy to follow, and functional. I've
fully unit tested everything.
