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
