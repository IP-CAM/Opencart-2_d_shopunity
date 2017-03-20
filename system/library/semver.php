<?php 
class Semver {

	public function gt($v1, $v2){
		return Semver\Comparator::greaterThan($v1, $v2);
	}

	public function lt($v1, $v2){
		return Semver\Comparator::lessThan($v1, $v2);
	}


	public function satisfies($v1, $v2){
		return Semver\Semver::satisfies($v1, $v2);
	}

	public function satisfiedBy(array $v1, $v2){
		return Semver\Semver::satisfiedBy($v1, $v2);
	}

}