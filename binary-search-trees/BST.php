#!/usr/bin/php7.0
<?php

class Node
{
    public $parent, $value, $left, $right;

    public function __construct($value, Node $parent = NULL)
    {
        $this->value = $value;
        $this->parent = $parent;
    }

    public function min()
    {
        $node = $this;
        while ($node->left) {
            if (!$node->left) {
                break;
            }
            $node = $node->left;
        }

        return $node;
    }

    public function max()
    {
        $node = $this;
        while ($node->right) {
            if (!$node->right) {
                break;
            }
            $node = $node->right;
        }

        return $node;
    }

    public function delete()
    {
        if ($this->left && $this->right) {
            $min = $this->right->min();
            $this->value = $min->value;
            $min->delete();
        } elseif ($this->right) {
            if ($this->parent->left === $this) {
                $this->parent->left = $this->right;
                $this->right->parent = $this->parent->left;
            } elseif ($this->parent->right === $this) {
                $this->parent->right = $this->right;
                $this->right->parent = $this->parent->right;
            }
            $this->parent = NULL;
            $this->right  = NULL;
        } elseif ($this->left) {
            if ($this->parent->left === $this) {
                $this->parent->left = $this->left;
                $this->left->parent = $this->parent->left;
            } elseif ($this->parent->right === $this) {
                $this->parent->right = $this->left;
                $this->left->parent = $this->parent->right;
            }
            $this->parent = NULL;
            $this->left   = NULL;
        } else {
            if ($this->parent->right === $this) {
                $this->parent->right = NULL;
            } elseif ($this->parent->left === $this) {
                $this->parent->left = NULL;
            }
            $this->parent = NULL;
        }
    }
}

class BST
{
    public $root;

    public function __construct($value = NULL)
    {
        if ($value !== NULL) {
            $this->root = new Node($value);
        }
    }

    public function search($value)
    {
        $node = $this->root;

        while ($node) {
            if ($value > $node->value) {
                $node = $node->right;
            } elseif ($value < $node->value) {
                $node = $node->left;
            } else {
                break;
            }
        }

        return $node;
    }

    public function insert($value)
    {
        $node = $this->root;
        if (!$node) {
            return $this->root = new Node($value);
        }

        while ($node) {
            if ($value > $node->value) {
                if ($node->right) {
                    $node = $node->right;
                } else {
                    $node = $node->right = new Node($value, $node);
                    break;
                }
            } elseif ($value < $node->value) {
                if ($node->left) {
                    $node = $node->left;
                } else {
                    $node = $node->left = new Node($value, $node);
                    break;
                }
            } else {
                break;
            }
        }

        return $node;
    }

    public function min()
    {
        if (!$this->root) {
            throw new Exception('Tree root is empty!');
        }

        $node = $this->root;

        return $node->min();
    }

    public function max()
    {
        if (!$this->root) {
            throw new Exception('Tree root is empty!');
        }

        $node = $this->root;

        return $node->max();
    }

    public function delete($value)
    {
        $node = $this->search($value);
        if ($node) {
            $node->delete();
        }
    }

    public function walk(Node $node = NULL)
    {
        if (!$node) {
            $node = $this->root;
        }
        if (!$node) {
            return FALSE;
        }
        if ($node->left) {
            yield from $this->walk($node->left);
        }
        yield $node;
        if ($node->right) {
            yield from $this->walk($node->right);
        }
    }
}

// Instantiate a new tree with root node of 5
$bst = new BST(5);

// Insert left branch nodes
$bst->insert(2);
$bst->insert(1);
$bst->insert(4);

// Insert right branch nodes
$bst->insert(11);
$bst->insert(7);
$bst->insert(23);
$bst->insert(16);
$bst->insert(34);

// Walk the tree
$tree = $bst->walk();
foreach ($tree as $node) {
    echo "{$node->value}\r\n";
}
