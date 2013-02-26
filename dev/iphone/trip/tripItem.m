//
//  tripItem.m
//  trip
//
//  Created by 沈 吾苓 on 13-1-27.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "tripItem.h"

@implementation tripItem

@synthesize title;
@synthesize time;
@synthesize bgimg;


- (id)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier
{
    self = [super initWithStyle:style reuseIdentifier:reuseIdentifier];
    if (self) {
        // Initialization code
    }
    return self;
}

- (void)setSelected:(BOOL)selected animated:(BOOL)animated
{
    [super setSelected:selected animated:animated];

    // Configure the view for the selected state
}

- (void)setTitle:(NSString *)t
{
    title = [t copy];
}

- (void)setTime:(NSString *)tm
{
    time = [tm copy];
}
- (void)setBgimg:(NSString *)bg
{
    bgimg = [bg copy];
}

- (void)render
{
    //重用 清空之前的view
    for (UIView *v in [self.contentView subviews]) {
        [v removeFromSuperview];
        v = nil;
    }
    
    UIView *cellBg = [[UIView alloc] initWithFrame:CGRectMake(5, 5, self.frame.size.width-10, 68)];
    cellBg.backgroundColor = [UIColor whiteColor];
    cellBg.alpha = 0.3;
    cellBg.layer.cornerRadius = 5;
    [self.contentView addSubview:cellBg];
    
    UIImageView *bgview = [[UIImageView alloc] initWithFrame:CGRectMake(10, 10, self.frame.size.width-20, 58)];
    bgview.layer.cornerRadius = 10;
    [bgview setImageWithURL:[NSURL URLWithString:bgimg]];
    
    CGRect modelFrame = CGRectMake(200, 10, 100, 50);
    UIView *modelView = [[UIView alloc] initWithFrame:modelFrame];
    modelView.backgroundColor = [UIColor blackColor];
    modelView.alpha = 0.6;
    
    CGRect titleFrame = CGRectMake(205, 20, 90, 20);
    UILabel *titleLabel = [[UILabel alloc] initWithFrame:titleFrame];
    titleLabel.text = title;
    titleLabel.textColor = [UIColor whiteColor];
    titleLabel.backgroundColor = [UIColor colorWithRed:0 green:0 blue:0 alpha:0];
    titleLabel.font = [UIFont systemFontOfSize:18];

    CGRect timeFrame = CGRectMake(205, 40, 90, 15);
    UILabel *timeLabel = [[UILabel alloc] initWithFrame:timeFrame];
    timeLabel.text = time;
    timeLabel.textColor = [UIColor whiteColor];
    timeLabel.backgroundColor = [UIColor colorWithRed:0 green:0 blue:0 alpha:0];
    timeLabel.font = [UIFont systemFontOfSize:12];
    
    [self.contentView addSubview:bgview];
    [self.contentView addSubview:modelView];
    [self.contentView addSubview:titleLabel];
    [self.contentView addSubview:timeLabel];
}

@end
