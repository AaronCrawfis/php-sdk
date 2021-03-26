<?php

/**
 * This file is automatically generated by `composer build-grpc`
 */

namespace Dapr\Client\Interfaces\V1;

/**
 * Enum describing the supported concurrency for state.
 *
 * Protobuf type <code>dapr.proto.common.v1.StateOptions.StateConcurrency</code>
 */
interface StateConcurrencyInterface
{
	/** Generated from protobuf enum <code>CONCURRENCY_UNSPECIFIED = 0;</code> */
	public const CONCURRENCY_UNSPECIFIED = 0;

	/** Generated from protobuf enum <code>CONCURRENCY_FIRST_WRITE = 1;</code> */
	public const CONCURRENCY_FIRST_WRITE = 1;

	/** Generated from protobuf enum <code>CONCURRENCY_LAST_WRITE = 2;</code> */
	public const CONCURRENCY_LAST_WRITE = 2;

	public static function name($value);


	public static function value($name);
}