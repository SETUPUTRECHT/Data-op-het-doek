function Voter (chair,hash) {
		this.chair = parseInt(chair);
		this.hash = hash;

		this.avgVoteLooks = 0;
		this.lowVoteLooks = 20;
		this.highVoteLooks = 0;
		this.voteCountLooks = 0;
		this.votePerVisualLooks = [];

		this.avgVoteInfo  = 0;
		this.avgVoteInfo = 0;
		this.lowVoteInfo = 20;
		this.highVoteInfo = 0;
		this.voteCountInfo = 0;
		this.votePerVisualInfo = [];
	}

	Voter.prototype.getInfo = function() {
		return this.color + ' ' + this.type + ' apple';
	};
	
	function Visual(name,maker,website) {
		this.name = name;
		this.maker = maker;
		this.website = website;
		this.avgVoteLooks = 0;
		this.voteCountLooks = 0;
		this.voteAbsoluteCountLooks = 0;
		this.avgVoteInfo  = 0;
		this.voteCountInfo = 0;
		this.voteAbsoluteCountInfo = 0;
	}
