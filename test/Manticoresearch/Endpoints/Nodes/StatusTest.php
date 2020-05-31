<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Endpoints;

use Manticoresearch\Endpoints\Nodes\Status;
use Manticoresearch\Test\Helper\PopulateHelperTest;

class StatusTest extends \PHPUnit\Framework\TestCase
{

    public function testGetPath(): void
    {
        $status = new Status();
        $this->assertEquals('/sql', $status->getPath());
    }

    public function testGetMethod(): void
    {
        $status = new Status();
        $this->assertEquals('POST', $status->getMethod());
    }

    public function testGetStatus(): void
    {
        $helper = new PopulateHelperTest();
        $client = $helper->getClient();
        $response = $client->nodes()->status();

        // cannot test values, uptime will never be consistent.  As such use keys instead
        $keys = \array_keys($response);
        \sort($keys);
        
        $this->assertEquals([
            'agent_connect',
            'agent_retry',
            'avg_dist_local',
            'avg_dist_wait',
            'avg_dist_wall',
            'avg_query_cpu',
            'avg_query_readkb',
            'avg_query_reads',
            'avg_query_readtime',
            'avg_query_wall',
            'cluster_name',
            'cluster_testcluster_apply_oooe',
            'cluster_testcluster_apply_oool',
            'cluster_testcluster_apply_window',
            'cluster_testcluster_causal_reads',
            'cluster_testcluster_cert_bucket_count',
            'cluster_testcluster_cert_deps_distance',
            'cluster_testcluster_cert_index_size',
            'cluster_testcluster_cert_interval',
            'cluster_testcluster_cluster_weight',
            'cluster_testcluster_commit_oooe',
            'cluster_testcluster_commit_oool',
            'cluster_testcluster_commit_window',
            'cluster_testcluster_conf_id',
            'cluster_testcluster_desync_count',
            'cluster_testcluster_evs_delayed',
            'cluster_testcluster_evs_evict_list',
            'cluster_testcluster_evs_repl_latency',
            'cluster_testcluster_evs_state',
            'cluster_testcluster_flow_control_interval',
            'cluster_testcluster_flow_control_interval_high',
            'cluster_testcluster_flow_control_interval_low',
            'cluster_testcluster_flow_control_paused',
            'cluster_testcluster_flow_control_paused_ns',
            'cluster_testcluster_flow_control_recv',
            'cluster_testcluster_flow_control_sent',
            'cluster_testcluster_flow_control_status',
            'cluster_testcluster_gcache_pool_size',
            'cluster_testcluster_gcomm_uuid',
            'cluster_testcluster_incoming_addresses',
            'cluster_testcluster_indexes',
            'cluster_testcluster_indexes_count',
            'cluster_testcluster_ist_receive_seqno_current',
            'cluster_testcluster_ist_receive_seqno_end',
            'cluster_testcluster_ist_receive_seqno_start',
            'cluster_testcluster_ist_receive_status',
            'cluster_testcluster_last_applied',
            'cluster_testcluster_last_committed',
            'cluster_testcluster_local_cached_downto',
            'cluster_testcluster_local_cert_failures',
            'cluster_testcluster_local_commits',
            'cluster_testcluster_local_index',
            'cluster_testcluster_local_recv_queue',
            'cluster_testcluster_local_recv_queue_avg',
            'cluster_testcluster_local_recv_queue_max',
            'cluster_testcluster_local_recv_queue_min',
            'cluster_testcluster_local_replays',
            'cluster_testcluster_local_send_queue',
            'cluster_testcluster_local_send_queue_avg',
            'cluster_testcluster_local_send_queue_max',
            'cluster_testcluster_local_send_queue_min',
            'cluster_testcluster_local_state',
            'cluster_testcluster_local_state_comment',
            'cluster_testcluster_local_state_uuid',
            'cluster_testcluster_node_state',
            'cluster_testcluster_nodes_set',
            'cluster_testcluster_nodes_view',
            'cluster_testcluster_open_connections',
            'cluster_testcluster_open_transactions',
            'cluster_testcluster_protocol_version',
            'cluster_testcluster_received',
            'cluster_testcluster_received_bytes',
            'cluster_testcluster_repl_data_bytes',
            'cluster_testcluster_repl_keys',
            'cluster_testcluster_repl_keys_bytes',
            'cluster_testcluster_repl_other_bytes',
            'cluster_testcluster_replicated',
            'cluster_testcluster_replicated_bytes',
            'cluster_testcluster_size',
            'cluster_testcluster_state_uuid',
            'cluster_testcluster_status',
            'command_callpq',
            'command_commit',
            'command_delete',
            'command_excerpt',
            'command_flushattrs',
            'command_insert',
            'command_json',
            'command_keywords',
            'command_persist',
            'command_replace',
            'command_search',
            'command_set',
            'command_status',
            'command_suggest',
            'command_update',
            'connections',
            'dist_local',
            'dist_queries',
            'dist_wait',
            'dist_wall',
            'maxed_out',
            'mysql_version',
            'qcache_cached_queries',
            'qcache_hits',
            'qcache_max_bytes',
            'qcache_thresh_msec',
            'qcache_ttl_sec',
            'qcache_used_bytes',
            'queries',
            'query_cpu',
            'query_readkb',
            'query_reads',
            'query_readtime',
            'query_wall',
            'uptime',
            'version',
            'work_queue_length',
            'workers_active',
            'workers_total',
        ], $keys);
    }

}
