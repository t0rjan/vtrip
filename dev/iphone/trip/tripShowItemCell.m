//
//  tripShowItemCell.m
//  trip
//
//  Created by 沈 吾苓 on 13-1-29.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "tripShowItemCell.h"

#define photoWidth 260

@implementation tripShowItemCell

@synthesize imgInfo;


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

- (void)render
{
    for (UIView *v in self.contentView.subviews) {
        [v removeFromSuperview];
        v = nil;
    }
    
    int imgHeight = [[imgInfo objectForKey:@"height_pin"] intValue];
    int minImgHeight = 120;
    imgHeight = MAX(imgHeight, minImgHeight);
    //白背景
    UIView *imgBg = [[UIView alloc] initWithFrame:CGRectMake(45, 0, photoWidth + 10, imgHeight+35)];
    imgBg.backgroundColor = [UIColor blackColor];
    imgBg.alpha = 0.5;
    [self.contentView addSubview:imgBg];
    

    UIImageView *imageView = [[UIImageView alloc] initWithFrame:CGRectMake(50, 5, photoWidth, [[imgInfo objectForKey:@"height_pin"] intValue])];
    [imageView setImageWithURL:[NSURL URLWithString:[imgInfo objectForKey:@"img_url_pin"]]];
    [self.contentView addSubview:imageView];
    
    
    CGRect titleFrame = CGRectMake(50, imgHeight+5 , 100, 30);
    UILabel *titleLabel = [[UILabel alloc] initWithFrame:titleFrame];
    titleLabel.text = [imgInfo objectForKey:@"content"];
    titleLabel.backgroundColor = [UIColor clearColor];
    titleLabel.textColor = [UIColor whiteColor];
    [self.contentView addSubview:titleLabel];
    
    self.contentView.frame = CGRectMake(0, 0, 320, [[imgInfo objectForKey:@"height_pin"] intValue]);
    
    mlViewElementTimeClock *clock = [[mlViewElementTimeClock alloc] initWithFrame:CGRectMake(5, 5, 0, 0)];
    [self.contentView addSubview:clock];
    
    
    NSMutableDictionary *dataInPool = [[NSMutableDictionary alloc] init];
    [dataInPool setObject:[self.imgInfo objectForKey:@"id"] forKey:@"photo_id"];
    
    self.anno = [[mlviewEleIconAnno alloc] initWithFrame:CGRectMake(6, 45, 0, 0)];
    self.anno.dataPool = dataInPool;
    [self.contentView addSubview:self.anno];
    
    self.like = [[[mlviewEleIconLike alloc] initWithFrame:CGRectMake(6, 90 , 0, 0)] autorelease];
    self.like.dataPool = dataInPool;
    self.like.number = 2;
    [self.contentView addSubview:self.like];
    
    self.comment = [[mlViewEleIconComment alloc] initWithFrame:CGRectMake(6, 135 , 0, 0)];
    self.comment.dataPool = dataInPool;
    [self.contentView addSubview:self.comment];
}
- (void)setIconDelegate:(id *)d
{
    self.like.delegate = (id)d;
    self.comment.delegate = (id)d;
}

@end
