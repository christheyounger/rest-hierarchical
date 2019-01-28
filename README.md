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

The problem is retrieving the entire graph from the database and presenting it
in a hierarchical format for the end user. Left to their own devices, the Serializer
and the ORM will traverse the graph by querying for branches of each Store that
it encounteres. This will inevitibly result in large numbers of queries when a
reasonable amount of data is added to a reasonable depth.

Adding 5 branches to each store, at a depth of 5 produces 3907 Database Queries
to build the graph. Obviously this is unacceptable.
